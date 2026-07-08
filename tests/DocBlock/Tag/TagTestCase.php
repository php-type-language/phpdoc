<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\DocBlockParserInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Platform\PlatformInterface;
use TypeLang\PhpDoc\TagFactoryInterface;
use TypeLang\PhpDoc\TagRegistryInterface;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
abstract class TagTestCase extends TestCase
{
    private static ?DocBlockParserInterface $defaultParser = null;

    /**
     * @param non-empty-string $tag
     */
    protected static function parseTag(string $tag): TagInterface
    {
        $tags = self::parseTags('/** ' . $tag . ' */');

        self::assertCount(1, $tags, 'The docblock is expected to contain a single tag');

        return $tags[0];
    }

    /**
     * @return list<TagInterface>
     */
    protected static function parseTags(string $docBlock): array
    {
        return (self::$defaultParser ??= DocBlockParser::createDefault())
            ->parse($docBlock)
            ->tags;
    }

    /**
     * @param array<non-empty-string, TagDefinitionInterface> $tags
     * @param array<non-empty-string, non-empty-string> $aliases
     */
    protected function createTagRegistry(
        array $tags = [],
        array $aliases = [],
    ): TagRegistryInterface {
        return new TagRegistry($tags, $aliases);
    }

    /**
     * @param array<non-empty-string, TagDefinitionInterface> $tags
     * @param array<non-empty-string, (callable(Cursor): mixed)> $combinators
     * @param array<non-empty-string, non-empty-string> $aliases
     */
    protected function createFactory(
        array $tags = [],
        array $combinators = [],
        array $aliases = [],
    ): TagFactoryInterface {
        $registry = $this->createTagRegistry($tags, $aliases);

        return new TagFactory($registry, $combinators);
    }

    /**
     * @param array<non-empty-string, TagDefinitionInterface> $tags
     * @param array<non-empty-string, (callable(Cursor): mixed)> $combinators
     * @param array<non-empty-string, non-empty-string> $aliases
     */
    protected function createPlatform(
        array $tags = [],
        array $combinators = [],
        array $aliases = [],
    ): PlatformInterface {
        return new readonly class('testing', $tags, $aliases, $combinators) implements PlatformInterface {
            public function __construct(
                /** @var non-empty-string */
                public string $name,
                /** @var iterable<non-empty-string, TagDefinitionInterface> */
                public iterable $tags,
                /** @var iterable<non-empty-string, non-empty-string> */
                public iterable $aliases,
                /** @var iterable<non-empty-string, (callable(Cursor): mixed)> */
                public iterable $combinators,
            ) {}
        };
    }

    /**
     * @param array<non-empty-string, TagDefinitionInterface> $tags
     * @param array<non-empty-string, (callable(Cursor): mixed)> $combinators
     * @param array<non-empty-string, non-empty-string> $aliases
     */
    protected function createDocBlockParser(
        array $tags = [],
        array $combinators = [],
        array $aliases = [],
    ): DocBlockParserInterface {
        $platform = $this->createPlatform($tags, $combinators, $aliases);

        return $this->createDocBlockParserWithPlatform($platform);
    }

    protected function createDocBlockParserWithPlatform(
        PlatformInterface ...$platform,
    ): DocBlockParserInterface {
        return new DocBlockParser($platform);
    }

    protected function createDefaultDocBlockParser(): DocBlockParserInterface
    {
        return DocBlockParser::createDefault();
    }
}
