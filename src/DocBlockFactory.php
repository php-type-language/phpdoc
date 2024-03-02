<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use JetBrains\PhpStorm\Language;
use Phplrt\Contracts\Lexer\LexerExceptionInterface;
use Phplrt\Contracts\Lexer\LexerRuntimeExceptionInterface;
use Phplrt\Contracts\Source\SourceExceptionInterface;
use TypeLang\PHPDoc\Exception\ParsingException;
use TypeLang\PHPDoc\Exception\RuntimeExceptionInterface;
use TypeLang\PHPDoc\Parser\Comment\CommentParserInterface;
use TypeLang\PHPDoc\Parser\Comment\LexerAwareCommentParser;
use TypeLang\PHPDoc\Parser\Comment\Segment;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Parser\Description\SprintfDescriptionReader;
use TypeLang\PHPDoc\Parser\SourceMap;
use TypeLang\PHPDoc\Parser\Tag\TagParser;
use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;
use TypeLang\PHPDoc\Tag\MutableRepositoryInterface;
use TypeLang\PHPDoc\Tag\Repository;
use TypeLang\PHPDoc\Tag\RepositoryInterface;

/**
 * @psalm-suppress UndefinedAttributeClass : JetBrains language attribute may not be available
 */
class DocBlockFactory implements DocBlockFactoryInterface
{
    private readonly CommentParserInterface $comment;

    private readonly DescriptionParserInterface $description;

    private readonly MutableRepositoryInterface $tags;

    private readonly TagParserInterface $parser;

    public function __construct(?RepositoryInterface $tags = null)
    {
        $this->comment = new LexerAwareCommentParser();
        $this->description = new SprintfDescriptionReader();

        $this->tags = $this->createRepository($tags);
        $this->parser = new TagParser($this->tags);
    }

    private function createRepository(?RepositoryInterface $tags): MutableRepositoryInterface
    {
        if ($tags instanceof MutableRepositoryInterface) {
            return $tags;
        }

        return new Repository($tags);
    }

    /**
     * @throws RuntimeExceptionInterface
     */
    public function create(#[Language('PHP')] string $docblock): DocBlock
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
     *
     * @throws LexerExceptionInterface
     * @throws LexerRuntimeExceptionInterface
     * @throws RuntimeExceptionInterface
     * @throws SourceExceptionInterface
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
                    $description = $this->description->parse($block, $this->parser);
                } else {
                    $tags[] = $this->parser->parse($block);
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
     *
     * @throws LexerExceptionInterface
     * @throws LexerRuntimeExceptionInterface
     * @throws SourceExceptionInterface
     */
    private function groupByCommentSections(string $docblock): \Generator
    {
        $current = '';
        $blocks = [];

        foreach ($this->comment->parse($docblock) as $segment) {
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
