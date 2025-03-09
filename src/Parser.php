<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\DocBlock\DocBlock;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\MethodTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\MutableTagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\ParamTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\PropertyReadTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\PropertyTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\PropertyWriteTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\ReturnTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TemplateCovariantTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TemplateExtendsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TemplateImplementsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TemplateTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\ThrowsTagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\VarTagFactory;
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

class Parser implements ParserInterface
{
    private readonly CommentParserInterface $comments;

    private readonly DescriptionParserInterface $descriptions;

    private readonly TagParserInterface $tags;

    private readonly MutableTagFactoryInterface $factories;

    /**
     * @param iterable<non-empty-string, TagFactoryInterface>|null $tags
     */
    public function __construct(?iterable $tags = null)
    {
        $this->factories = new TagFactory($tags ?? self::createDefaultTags());
        $this->tags = new RegexTagParser($this->factories);
        $this->descriptions = new RegexDescriptionParser($this->tags);
        $this->comments = new RegexCommentParser();
    }

    /**
     * @return iterable<non-empty-string, TagFactoryInterface>
     */
    private static function createDefaultTags(): iterable
    {
        yield 'method' => new MethodTagFactory();
        yield 'param' => new ParamTagFactory();
        yield 'property' => new PropertyTagFactory();
        yield 'property-read' => new PropertyReadTagFactory();
        yield 'property-write' => new PropertyWriteTagFactory();
        yield 'return' => new ReturnTagFactory();
        yield 'throws' => new ThrowsTagFactory();
        yield 'var' => new VarTagFactory();
        yield 'template' => new TemplateTagFactory();
        yield 'template-implements' => $implements = new TemplateImplementsTagFactory();
        yield 'implements' => $implements;
        yield 'template-extends' => $extends = new TemplateExtendsTagFactory();
        yield 'extends' => $extends;
        yield 'template-covariant' => new TemplateCovariantTagFactory();
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
