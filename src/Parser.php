<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\DocBlock\DocBlock;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\MutableTagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Exception\ParsingException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Comment\CommentParserInterface;
use TypeLang\PHPDoc\Parser\Comment\RegexCommentParser;
use TypeLang\PHPDoc\Parser\Comment\Segment;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Parser\Description\RegexDescriptionParser;
use TypeLang\PHPDoc\Parser\SourceMap;
use TypeLang\PHPDoc\Parser\Tag\RegexTagParser;
use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;
use TypeLang\PHPDoc\Platform\CompoundPlatform;
use TypeLang\PHPDoc\Platform\PhanPlatform;
use TypeLang\PHPDoc\Platform\PHPStanPlatform;
use TypeLang\PHPDoc\Platform\PlatformInterface;
use TypeLang\PHPDoc\Platform\PsalmPlatform;
use TypeLang\PHPDoc\Platform\StandardPlatform;

class Parser implements ParserInterface
{
    private readonly CommentParserInterface $comments;

    private readonly DescriptionParserInterface $descriptions;

    private readonly TagParserInterface $tags;

    private readonly MutableTagFactoryInterface $factories;

    public function __construct(
        public readonly PlatformInterface $platform = new CompoundPlatform([
            new StandardPlatform(),
            new PsalmPlatform(),
            new PHPStanPlatform(),
            new PhanPlatform(),
        ]),
    ) {
        $this->factories = new TagFactory($platform->getTags());
        $this->tags = $this->createTagParser($this->factories);
        $this->descriptions = $this->createDescriptionParser($this->tags);
        $this->comments = $this->createCommentParser();
    }

    protected function createTagParser(TagFactoryInterface $factories): TagParserInterface
    {
        return new RegexTagParser($factories);
    }

    protected function createDescriptionParser(TagParserInterface $tags): DescriptionParserInterface
    {
        return new RegexDescriptionParser($tags);
    }

    protected function createCommentParser(): CommentParserInterface
    {
        return new RegexCommentParser();
    }

    /**
     * Facade method of {@see MutableTagFactoryInterface::register()}
     *
     * @param non-empty-string|list<non-empty-string> $tags
     */
    public function register(string|array $tags, TagFactoryInterface $delegate): void
    {
        $this->factories->register($tags, $delegate);
    }

    /**
     * @throws RuntimeExceptionInterface
     */
    public function parse(string $docblock): DocBlock
    {
        $mapper = new SourceMap();

        try {
            /** @var Segment $segment */
            foreach ($result = $this->analyze($docblock) as $segment) {
                $mapper->add($segment->offset, $segment->text);
            }
        } catch (RuntimeExceptionInterface $e) {
            throw $e->withSource(
                source: $docblock,
                offset: $mapper->getOffset($e->getOffset()),
            );
        } catch (\Throwable $e) {
            throw ParsingException::fromInternalError(
                source: $docblock,
                offset: $mapper->getOffset(0),
                e: $e,
            );
        }

        return $result->getReturn();
    }

    /**
     * @return \Generator<array-key, Segment, void, DocBlock>
     * @throws RuntimeExceptionInterface
     */
    private function analyze(string $docblock): \Generator
    {
        yield from $blocks = $this->groupByCommentSections($docblock);

        $description = null;
        $tags = [];
        $offset = 0;

        foreach ($blocks->getReturn() as $block) {
            try {
                if ($description === null) {
                    $description = $this->descriptions->parse($block);
                } else {
                    $tags[] = $this->tags->parse($block, $this->descriptions);
                }
            } catch (RuntimeExceptionInterface $e) {
                throw $e->withSource(
                    source: $docblock,
                    offset: $offset + $e->getOffset(),
                );
            } catch (\Throwable $e) {
                throw ParsingException::fromInternalError(
                    source: $docblock,
                    offset: $offset,
                    e: $e,
                );
            }

            $offset += \strlen($block);
        }

        return new DocBlock($description, $tags);
    }

    /**
     * @return \Generator<array-key, Segment, void, non-empty-list<string>>
     */
    private function groupByCommentSections(string $docblock): \Generator
    {
        $current = '';
        $blocks = [];

        foreach ($this->comments->parse($docblock) as $segment) {
            yield $segment;

            if (\str_starts_with($segment->text, '@')) {
                $blocks[] = $current;
                $current = '';
            }

            $current .= $segment->text;
        }

        return [...$blocks, $current];
    }
}
