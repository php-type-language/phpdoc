<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\ExampleTag\ExampleTag;
use TypeLang\PhpDoc\DocBlock\Tag\ExampleTag\ExampleTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class ExampleTagTest extends TestCase
{
    #[Test]
    public function parsesLocationWithStartCountAndDescription(): void
    {
        $tag = self::factory()->create('example', 'https://example.com/demo.php 12 30 A relevant excerpt.');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertNotNull($tag->location);
        self::assertSame('https://example.com/demo.php', (string) $tag->location);
        self::assertSame(12, $tag->start);
        self::assertSame(30, $tag->count);
        self::assertSame('A relevant excerpt.', (string) $tag->description);
        self::assertSame('@example https://example.com/demo.php 12 30 A relevant excerpt.', (string) $tag);
    }

    #[Test]
    public function parsesLocationWithStartOnly(): void
    {
        $tag = self::factory()->create('example', 'demo.php 7');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertNotNull($tag->location);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertSame(7, $tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@example demo.php 7', (string) $tag);
    }

    #[Test]
    public function parsesLocationOnly(): void
    {
        $tag = self::factory()->create('example', 'demo.php');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertNotNull($tag->location);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertNull($tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@example demo.php', (string) $tag);
    }

    #[Test]
    public function treatsLeadingWordAsLocation(): void
    {
        $tag = self::factory()->create('example', 'demo.php the bundled snippet below.');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertNotNull($tag->location);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertSame('the bundled snippet below.', (string) $tag->description);
        self::assertSame('@example demo.php the bundled snippet below.', (string) $tag);
    }

    #[Test]
    public function rejectsMissingLocation(): void
    {
        $tag = self::factory()->create('example', '// inline example');

        self::assertInstanceOf(InvalidTag::class, $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @example demo.php 3 5 */');

        self::assertInstanceOf(ExampleTag::class, $block->tags[0]);
        self::assertSame('demo.php', (string) $block->tags[0]->location);
        self::assertSame(3, $block->tags[0]->start);
        self::assertSame(5, $block->tags[0]->count);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            ExampleTagDefinition::NAME => new ExampleTagDefinition(),
        ]);

        return new TagFactory($registry, [
            UrlCombinator::NAME => new UrlCombinator(),
            UriCombinator::NAME => new UriCombinator(),
            IntegerCombinator::NAME => new IntegerCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
