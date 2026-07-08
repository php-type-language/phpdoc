<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\ExampleTag\ExampleTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;

final class ExampleTagTest extends TagTestCase
{
    #[Test]
    public function parsesLocationWithStartCountAndDescription(): void
    {
        $tag = self::parseTag('@example https://example.com/demo.php 12 30 A relevant excerpt.');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertSame('example', $tag->name);
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
        $tag = self::parseTag('@example demo.php 7');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertSame(7, $tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@example demo.php 7', (string) $tag);
    }

    #[Test]
    public function parsesLocationOnly(): void
    {
        $tag = self::parseTag('@example demo.php');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertNull($tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@example demo.php', (string) $tag);
    }

    #[Test]
    public function treatsTrailingWordsAsDescription(): void
    {
        $tag = self::parseTag('@example demo.php the bundled snippet below.');

        self::assertInstanceOf(ExampleTag::class, $tag);
        self::assertSame('demo.php', (string) $tag->location);
        self::assertSame('the bundled snippet below.', (string) $tag->description);
        self::assertSame('@example demo.php the bundled snippet below.', (string) $tag);
    }

    #[Test]
    public function rejectsMissingLocation(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@example // inline example'));
    }
}
