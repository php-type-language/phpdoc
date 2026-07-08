<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\SourceTag\SourceTag;

final class SourceTagTest extends TagTestCase
{
    #[Test]
    public function parsesStartCountAndDescription(): void
    {
        $tag = self::parseTag('@source 12 30 The relevant excerpt.');

        self::assertInstanceOf(SourceTag::class, $tag);
        self::assertSame('source', $tag->name);
        self::assertSame(12, $tag->start);
        self::assertSame(30, $tag->count);
        self::assertSame('The relevant excerpt.', (string) $tag->description);
        self::assertSame('@source 12 30 The relevant excerpt.', (string) $tag);
    }

    #[Test]
    public function parsesStartOnly(): void
    {
        $tag = self::parseTag('@source 7');

        self::assertInstanceOf(SourceTag::class, $tag);
        self::assertSame(7, $tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@source 7', (string) $tag);
    }

    #[Test]
    public function rejectsNegativeStart(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@source -1'));
    }
}
