<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\Tests\TestCase;

final class InvalidTagTest extends TestCase
{
    #[Test]
    public function constructorStoresName(): void
    {
        $tag = new InvalidTag(new \RuntimeException(), 'param');

        $this->assertSame('param', $tag->name);
    }

    #[Test]
    public function constructorStoresReason(): void
    {
        $reason = new \RuntimeException('broken');
        $tag = new InvalidTag($reason, 'param');

        $this->assertSame($reason, $tag->reason);
    }

    #[Test]
    public function descriptionDefaultsToNull(): void
    {
        $this->assertNull(new InvalidTag(new \RuntimeException(), 'param')->description);
    }

    #[Test]
    public function isAnInvalidTag(): void
    {
        $tag = new InvalidTag(new \RuntimeException(), 'param');

        $this->assertInstanceOf(Tag::class, $tag);
    }
}
