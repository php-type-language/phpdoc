<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\AccessTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Visibility;

final class AccessTagTest extends TagTestCase
{
    #[Test]
    public function parsesVisibilityAndDescription(): void
    {
        $tag = self::parseTag('@access protected Internal API.');

        self::assertInstanceOf(AccessTag::class, $tag);
        self::assertSame('access', $tag->name);
        self::assertSame(Visibility::Protected, $tag->access);
        self::assertSame('Internal API.', (string) $tag->description);
        self::assertSame('@access protected Internal API.', (string) $tag);
    }

    #[Test]
    public function parsesVisibilityOnly(): void
    {
        $tag = self::parseTag('@access private');

        self::assertInstanceOf(AccessTag::class, $tag);
        self::assertSame(Visibility::Private, $tag->access);
        self::assertNull($tag->description);
        self::assertSame('@access private', (string) $tag);
    }

    #[Test]
    public function parsesPublicVisibility(): void
    {
        $tag = self::parseTag('@access public');

        self::assertInstanceOf(AccessTag::class, $tag);
        self::assertSame(Visibility::Public, $tag->access);
    }

    #[Test]
    public function rejectsUnknownVisibility(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@access package'));
    }

    #[Test]
    public function rejectsMissingVisibility(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@access'));
    }
}
