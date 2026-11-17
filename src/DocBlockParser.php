<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\ParsingException;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;
use TypeLang\PhpDoc\Exception\TagParsingException;
use TypeLang\PhpDoc\Internal\BaseCombinators;
use TypeLang\PhpDoc\Internal\BuiltinPlatforms;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\DocBlockAnalyzer;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;
use TypeLang\PhpDoc\Parser\Splitter\StringSplitter;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\Parser\Tag\TagParserInterface;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistryBuilder;
use TypeLang\PhpDoc\Platform\PlatformInterface;

/**
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final class DocBlockParser implements DocBlockParserInterface
{
    public readonly TagFactoryInterface $factory;

    public readonly TagRegistryInterface $tags;

    private readonly DocBlockAnalyzer $docBlockAnalyzer;
    private readonly TagParserInterface $tagParser;
    private readonly DescriptionParserInterface $descriptionParser;

    /**
     * @param iterable<mixed, PlatformInterface> $platforms tag platforms, a
     *        later one overrides an entry of an earlier one with the same name
     * @param TypeParser $typeParser parser of the types inside tags
     */
    public function __construct(
        iterable $platforms = [],
        TypeParser $typeParser = new TypeParser(),
    ) {
        $platforms = \is_array($platforms)
            ? \array_values($platforms)
            : \iterator_to_array($platforms, false);

        $this->tags = $this->createTagRegistry($platforms);
        $this->factory = $this->createTagFactory(
            registry: $this->tags,
            combinators: $this->createCombinators($platforms, $typeParser),
        );

        $this->docBlockAnalyzer = $this->createAnalyzer($this->createDocBlockSplitter());
        $this->tagParser = $this->createTagParser($this->factory);
        $this->descriptionParser = $this->createDescriptionParser($this->tagParser);
    }

    /**
     * Creates the parser with every platform shipped with the library.
     *
     * @param iterable<mixed, PlatformInterface> $additionalPlatforms platforms
     *        loaded after the built-in ones, overriding their entries with the
     *        same name
     * @param TypeParser $typeParser parser of the types inside tags
     */
    public static function createDefault(
        iterable $additionalPlatforms = [],
        TypeParser $typeParser = new TypeParser(),
    ): self {
        return new self([
            ...BuiltinPlatforms::create(),
            ...(\is_array($additionalPlatforms)
                ? \array_values($additionalPlatforms)
                : \iterator_to_array($additionalPlatforms, false)),
        ], $typeParser);
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
            foreach ($platform->getTags() as $name => $definition) {
                $definitions[$name] = $definition;
            }

            foreach ($platform->getAliases() as $alias => $canonical) {
                $aliases[$alias] = $canonical;
            }
        }

        $builder = new TagRegistryBuilder($definitions, $aliases);

        return $builder->build();
    }

    /**
     * @param list<PlatformInterface> $platforms
     * @return array<non-empty-string, CombinatorType>
     */
    private function createCombinators(array $platforms, TypeParser $typeParser): array
    {
        $combinators = BaseCombinators::create($typeParser);

        // A platform combinator replaces the base one or the one of an earlier
        // platform with the same name for the tags of all platforms.
        foreach ($platforms as $platform) {
            foreach ($platform->getCombinators() as $name => $combinator) {
                $combinators[$name] = $combinator;
            }
        }

        $description = null;

        // Description is always present and cannot be redefined.
        //
        // The combinator is created lazily on the first call.
        $combinators[DescriptionCombinator::NAME] = function (Cursor $cursor) use (&$description): mixed {
            $description ??= new DescriptionCombinator(
                descriptionParser: $this->descriptionParser,
            );

            return $description($cursor);
        };

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

        // An internal (non-library) failure is wrapped into a tag parsing
        // exception carrying the whole docblock and the failing offset.
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
