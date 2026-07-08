<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSinkTag\PsalmTaintSinkTag;

final class PsalmTaintSinkTagTest extends TagTestCase
{
    #[Test]
    public function parsesTaintTypeAndVariable(): void
    {
        $tag = self::parseTag('@psalm-taint-sink html $output');

        self::assertInstanceOf(PsalmTaintSinkTag::class, $tag);
        self::assertSame('psalm-taint-sink', $tag->name);
        self::assertSame('html', $tag->taint);
        self::assertSame('output', $tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@psalm-taint-sink html $output', (string) $tag);
    }

    #[Test]
    public function rejectsMissingVariable(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@psalm-taint-sink html'));
    }

    #[Test]
    public function rejectsEmptyBody(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@psalm-taint-sink'));
    }
}
