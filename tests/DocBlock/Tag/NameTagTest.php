<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\NameTag\NameTag;

final class NameTagTest extends TagTestCase
{
    #[Test]
    public function parsesAliasAndDescription(): void
    {
        $tag = self::parseTag('@name globalConfig The shared configuration.');

        self::assertInstanceOf(NameTag::class, $tag);
        self::assertSame('name', $tag->name);
        self::assertSame('globalConfig', $tag->alias);
        self::assertSame('The shared configuration.', (string) $tag->description);
        self::assertSame('@name globalConfig The shared configuration.', (string) $tag);
    }

    #[Test]
    public function parsesAliasOnly(): void
    {
        $tag = self::parseTag('@name homepage');

        self::assertInstanceOf(NameTag::class, $tag);
        self::assertSame('homepage', $tag->alias);
        self::assertNull($tag->description);
        self::assertSame('@name homepage', (string) $tag);
    }

    #[Test]
    public function rejectsMissingAlias(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@name'));
    }
}
