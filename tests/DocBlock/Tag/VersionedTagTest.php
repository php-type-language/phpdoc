<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VersionCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag\DeprecatedTag;
use TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag\DeprecatedTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SinceTag\SinceTag;
use TypeLang\PhpDoc\DocBlock\Tag\SinceTag\SinceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VersionedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\VersionTag\VersionTag;
use TypeLang\PhpDoc\DocBlock\Tag\VersionTag\VersionTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class VersionedTagTest extends TestCase
{
    #[Test]
    public function parsesVersionAndDescription(): void
    {
        $tag = self::factory()->create('since', '8.0.0 Available on modern runtimes.');

        self::assertInstanceOf(SinceTag::class, $tag);
        self::assertInstanceOf(VersionedTagInterface::class, $tag);
        self::assertSame('since', $tag->name);
        self::assertSame('8.0.0', $tag->version);
        self::assertSame('Available on modern runtimes.', (string) $tag->description);
        self::assertSame('@since 8.0.0 Available on modern runtimes.', (string) $tag);
    }

    /**
     * A body that does not begin with a digit carries no version; it is all
     * description.
     */
    #[Test]
    public function bodyWithoutVersionIsAllDescription(): void
    {
        $tag = self::factory()->create('deprecated', 'Use the new API instead.');

        self::assertInstanceOf(DeprecatedTag::class, $tag);
        self::assertNull($tag->version);
        self::assertSame('Use the new API instead.', (string) $tag->description);
        self::assertSame('@deprecated Use the new API instead.', (string) $tag);
    }

    #[Test]
    public function emptyBodyHasNeitherVersionNorDescription(): void
    {
        $tag = self::factory()->create('version', '');

        self::assertInstanceOf(VersionTag::class, $tag);
        self::assertNull($tag->version);
        self::assertNull($tag->description);
        self::assertSame('@version', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @version 1.2.3 */');

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(VersionTag::class, $block->tags[0]);
        self::assertSame('1.2.3', $block->tags[0]->version);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: [
                VersionTagDefinition::NAME => new VersionTagDefinition(),
                SinceTagDefinition::NAME => new SinceTagDefinition(),
                DeprecatedTagDefinition::NAME => new DeprecatedTagDefinition(),
            ],
            combinators: [
                VersionCombinator::NAME => new VersionCombinator(),
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
