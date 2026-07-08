<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\AuthorTag\AuthorTag;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;

final class AuthorTagTest extends TagTestCase
{
    #[Test]
    public function parsesNameAndEmail(): void
    {
        $tag = self::parseTag('@author John Doe <john@example.com>');

        self::assertInstanceOf(AuthorTag::class, $tag);
        self::assertSame('author', $tag->name);
        self::assertSame('John Doe', $tag->author);
        self::assertSame('john@example.com', $tag->email);
        self::assertSame('@author John Doe <john@example.com>', (string) $tag);
    }

    #[Test]
    public function parsesNameWithoutEmail(): void
    {
        $tag = self::parseTag('@author Jane Roe');

        self::assertInstanceOf(AuthorTag::class, $tag);
        self::assertSame('Jane Roe', $tag->author);
        self::assertNull($tag->email);
        self::assertSame('@author Jane Roe', (string) $tag);
    }

    #[Test]
    public function rejectsEmailWithoutName(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@author <john@example.com>'));
    }

    #[Test]
    public function rejectsEmptyBody(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@author'));
    }
}
