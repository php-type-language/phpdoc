<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

final class InvalidTagTest extends TagTestCase
{
    #[Test]
    public function constructorStoresName(): void
    {
        $tag = new InvalidTag(new \RuntimeException(), 'param');

        self::assertSame('param', $tag->name);
    }

    #[Test]
    public function constructorStoresReason(): void
    {
        $reason = new \RuntimeException('broken');
        $tag = new InvalidTag($reason, 'param');

        self::assertSame($reason, $tag->reason);
    }

    #[Test]
    public function descriptionDefaultsToNull(): void
    {
        self::assertNull(new InvalidTag(new \RuntimeException(), 'param')->description);
    }

    #[Test]
    public function isAnInvalidTag(): void
    {
        $tag = new InvalidTag(new \RuntimeException(), 'param');

        self::assertInstanceOf(Tag::class, $tag);
    }
}
