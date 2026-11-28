<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\InvalidTagAliasException;
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
use TypeLang\Type\TypeNode;

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
     * @param iterable<mixed, PlatformInterface>|null $platforms tag
     *        platforms, a later one overrides an entry of an earlier one with
     *        the same name, every platform shipped with the library in case
     *        of {@see null}
     * @param TypeParser<TypeNode> $typeParser parser of the types inside tags
     * @throws InvalidTagAliasException in case of an alias of the platforms
     *         cannot be resolved to a definition
     */
    public function __construct(
        ?iterable $platforms = null,
        TypeParser $typeParser = new TypeParser(),
    ) {
        // Boot all tag definitions
        $this->tags = self::createTagRegistry($platforms = match (true) {
            $platforms === null => BuiltinPlatforms::create(),
            \is_array($platforms) => \array_values($platforms),
            default => \iterator_to_array($platforms, false),
        });

        $this->factory = self::createTagFactory(
            registry: $this->tags,
            combinators: $this->createCombinators(
                platforms: $platforms,
                typeParser: $typeParser->forType(),
            ),
        );

        $this->docBlockAnalyzer = self::createAnalyzer(
            splitter: self::createDocBlockSplitter(),
        );

        $this->tagParser = self::createTagParser($this->factory);
        $this->descriptionParser = self::createDescriptionParser($this->tagParser);
    }

    /**
     * Creates the parser with every platform shipped with the library.
     *
     * @param iterable<mixed, PlatformInterface> $additionalPlatforms platforms
     *        loaded after the built-in ones, overriding their entries with the
     *        same name
     * @param TypeParser<TypeNode> $typeParser parser of the types inside tags
     * @throws InvalidTagAliasException in case of an alias of the platforms
     *         cannot be resolved to a definition
     */
    public static function create(
        iterable $additionalPlatforms = [],
        TypeParser $typeParser = new TypeParser(),
    ): self {
        if ($additionalPlatforms instanceof \Traversable) {
            $additionalPlatforms = \iterator_to_array($additionalPlatforms, false);
        }

        return new self([
            ...BuiltinPlatforms::create(),
            ...\array_values($additionalPlatforms),
        ], $typeParser);
    }

    private static function createAnalyzer(SplitterInterface $splitter): DocBlockAnalyzer
    {
        return new DocBlockAnalyzer($splitter);
    }

    private static function createDocBlockSplitter(): SplitterInterface
    {
        return new StringSplitter();
    }

    /**
     * @param list<PlatformInterface> $platforms
     * @throws InvalidTagAliasException in case of an alias cannot be resolved
     *         to a definition
     */
    private static function createTagRegistry(array $platforms): TagRegistryInterface
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
    private static function createTagFactory(TagRegistryInterface $registry, array $combinators): TagFactoryInterface
    {
        return new TagFactory(
            registry: $registry,
            combinators: $combinators,
        );
    }

    private static function createTagParser(TagFactoryInterface $factory): TagParserInterface
    {
        return new StringTagParser($factory);
    }

    private static function createDescriptionParser(TagParserInterface $parser): DescriptionParserInterface
    {
        return new BalancedBraceAwareParser($parser);
    }

    /**
     * @param list<Segment> $segments
     * @return list<TagInterface>
     * @throws PhpDocExceptionInterface in case of a tag cannot be parsed
     */
    private function createTags(array $segments, string $docblock): array
    {
        $result = [];

        foreach ($segments as $segment) {
            try {
                $result[] = $this->tagParser->parse($segment->text);
            } catch (\Throwable $e) {
                throw $this->createFailure($e, $docblock, $segment->offset);
            }
        }

        return $result;
    }

    /**
     * @throws PhpDocExceptionInterface in case of the description cannot be
     *        parsed
     */
    private function tryCreateDescription(?Segment $segment, string $docblock): ?DescriptionInterface
    {
        if ($segment === null) {
            return null;
        }

        try {
            return $this->descriptionParser->tryParse($segment->text);
        } catch (\Throwable $e) {
            throw $this->createFailure($e, $docblock, $segment->offset);
        }
    }

    /**
     * Guarantees that only a {@see PhpDocExceptionInterface} leaves the parser.
     *
     * A parsing error is rebased onto the full docblock: its offset becomes
     * absolute (e.g. the position of the tag that failed to parse). Any other,
     * internal failure is wrapped as a {@see TagParsingException} at the same
     * location.
     *
     * @param int<0, max> $offset byte offset of the failing section inside
     *        $source
     */
    private function createFailure(\Throwable $e, string $source, int $offset): PhpDocExceptionInterface
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

    /**
     * @throws PhpDocExceptionInterface in case of the docblock cannot be parsed
     */
    public function parse(#[Language('InjectablePHP')] string $docblock): DocBlock
    {
        $data = $this->docBlockAnalyzer->analyze($docblock);

        return new DocBlock(
            description: $this->tryCreateDescription($data->description, $docblock),
            tags: $this->createTags($data->tags, $docblock),
        );
    }
}
