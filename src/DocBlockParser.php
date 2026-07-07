<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\ParsingException;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;
use TypeLang\PhpDoc\Exception\TagParsingException;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\DocBlockAnalyzer;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;
use TypeLang\PhpDoc\Parser\Splitter\StringSplitter;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\Parser\Tag\TagParserInterface;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Platform\PlatformInterface;
use TypeLang\PhpDoc\Platform\StandardPlatform;

/**
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final readonly class DocBlockParser implements DocBlockParserInterface
{
    public TagFactoryInterface $factory;

    public TagRegistryInterface $tags;

    private DocBlockAnalyzer $docBlockAnalyzer;
    private TagParserInterface $tagParser;
    private DescriptionParserInterface $descriptionParser;

    /**
     * @param iterable<PlatformInterface> $platforms additional platforms that
     *        extend the always-present {@see StandardPlatform}
     */
    public function __construct(iterable $platforms = [])
    {
        // The standard platform is always loaded first; the caller's platforms
        // extend it, overriding an entry when they reuse its name.
        $all = [new StandardPlatform()];

        foreach ($platforms as $platform) {
            $all[] = $platform;
        }

        $this->docBlockAnalyzer = $this->createAnalyzer(
            splitter: $this->createDocBlockSplitter(),
        );

        $this->tags = $this->createTagRegistry($all);
        $this->factory = $this->createTagFactory($this->tags, $this->createCombinators($all));

        $this->tagParser = $this->createTagParser($this->factory);
        $this->descriptionParser = $this->createDescriptionParser($this->tagParser);
    }

    private function createAnalyzer(SplitterInterface $splitter): DocBlockAnalyzer
    {
        return new DocBlockAnalyzer($splitter);
    }

    private function createDocBlockSplitter(): SplitterInterface
    {
        return new StringSplitter();
    }

    /**
     * @param list<PlatformInterface> $platforms
     */
    private function createTagRegistry(array $platforms): TagRegistryInterface
    {
        $definitions = [];
        $aliases = [];

        foreach ($platforms as $platform) {
            foreach ($platform->tags as $name => $definition) {
                $definitions[$name] = $definition;
            }

            foreach ($platform->aliases as $alias => $canonical) {
                $aliases[$alias] = $canonical;
            }
        }

        return new TagRegistry($definitions, $aliases);
    }

    /**
     * @param list<PlatformInterface> $platforms
     * @return array<non-empty-string, CombinatorType>
     */
    private function createCombinators(array $platforms): array
    {
        $combinators = [];

        foreach ($platforms as $platform) {
            foreach ($platform->combinators as $name => $combinator) {
                $combinators[$name] = $combinator;
            }
        }

        // Description is always present and cannot be redefined
        $combinators[DescriptionCombinator::NAME] = new \ReflectionClass(DescriptionCombinator::class)
            ->newLazyProxy(fn(): DescriptionCombinator => new DescriptionCombinator(
                descriptionParser: $this->descriptionParser,
            ));

        return $combinators;
    }

    /**
     * @param array<non-empty-string, CombinatorType> $combinators
     */
    private function createTagFactory(TagRegistryInterface $registry, array $combinators): TagFactoryInterface
    {
        return new TagFactory(
            registry: $registry,
            combinators: $combinators,
        );
    }

    private function createTagParser(TagFactoryInterface $factory): TagParserInterface
    {
        return new StringTagParser($factory);
    }

    private function createDescriptionParser(TagParserInterface $parser): DescriptionParserInterface
    {
        return new BalancedBraceAwareParser($parser);
    }

    /**
     * @param list<Segment> $segments
     * @return list<TagInterface>
     * @throws PhpDocExceptionInterface
     */
    private function createTags(array $segments, string $docblock): array
    {
        $result = [];

        foreach ($segments as $segment) {
            try {
                $result[] = $this->tagParser->parse($segment->text);
            } catch (\Throwable $e) {
                throw $this->failure($e, $docblock, $segment->offset);
            }
        }

        return $result;
    }

    /**
     * @throws PhpDocExceptionInterface
     */
    private function tryCreateDescription(?Segment $segment, string $docblock): ?DescriptionInterface
    {
        if ($segment === null) {
            return null;
        }

        try {
            return $this->descriptionParser->tryParse($segment->text);
        } catch (\Throwable $e) {
            throw $this->failure($e, $docblock, $segment->offset);
        }
    }

    /**
     * Guarantees that only a {@see PhpDocExceptionInterface} leaves the parser.
     *
     * A parsing error is rebased onto the full docblock so that its offset
     * becomes absolute (e.g. the position of the tag that failed to parse). Any
     * other, internal failure is wrapped as a {@see TagParsingException} at the
     * same location.
     *
     * @param int<0, max> $offset byte offset of the failing section inside $source
     */
    private function failure(\Throwable $e, string $source, int $offset): PhpDocExceptionInterface
    {
        // A parsing error carries an offset relative to the failing section;
        // rebasing it onto $source turns that into an absolute location.
        if ($e instanceof ParsingException) {
            return $e->withSource($source, $e->offset + $offset);
        }

        // Any other library error is already contractual and passes through.
        if ($e instanceof PhpDocExceptionInterface) {
            return $e;
        }

        // An internal (non-library) failure is wrapped so that the whole
        // docblock and the failing offset are still reported to the caller.
        return TagParsingException::becauseInternalErrorOccurs($e, $source, $offset);
    }

    public function parse(#[Language('InjectablePHP')] string $docblock): DocBlock
    {
        $data = $this->docBlockAnalyzer->analyze($docblock);

        return new DocBlock(
            description: $this->tryCreateDescription($data->description, $docblock),
            tags: $this->createTags($data->tags, $docblock),
        );
    }
}
