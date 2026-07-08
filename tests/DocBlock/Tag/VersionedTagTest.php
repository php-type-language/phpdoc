<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\DeprecatedTag\DeprecatedTag;
use TypeLang\PhpDoc\DocBlock\Tag\SinceTag\SinceTag;
use TypeLang\PhpDoc\DocBlock\Tag\VersionedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\VersionTag\VersionTag;

final class VersionedTagTest extends TagTestCase
{
    #[Test]
    public function parsesVersionAndDescription(): void
    {
        $tag = self::parseTag('@since 8.0.0 Available on modern runtimes.');

        self::assertInstanceOf(SinceTag::class, $tag);
        self::assertInstanceOf(VersionedTagInterface::class, $tag);
        self::assertSame('since', $tag->name);
        self::assertSame('8.0.0', $tag->version);
        self::assertSame('Available on modern runtimes.', (string) $tag->description);
        self::assertSame('@since 8.0.0 Available on modern runtimes.', (string) $tag);
    }

    #[Test]
    public function bodyWithoutVersionIsAllDescription(): void
    {
        $tag = self::parseTag('@deprecated Use the new API instead.');

        self::assertInstanceOf(DeprecatedTag::class, $tag);
        self::assertNull($tag->version);
        self::assertSame('Use the new API instead.', (string) $tag->description);
        self::assertSame('@deprecated Use the new API instead.', (string) $tag);
    }

    #[Test]
    public function parsesVersionWithoutDescription(): void
    {
        $tag = self::parseTag('@deprecated 1.0.0');

        self::assertInstanceOf(DeprecatedTag::class, $tag);
        self::assertSame('1.0.0', $tag->version);
        self::assertNull($tag->description);
        self::assertSame('@deprecated 1.0.0', (string) $tag);
    }

    #[Test]
    public function emptyBodyHasNeitherVersionNorDescription(): void
    {
        $tag = self::parseTag('@version');

        self::assertInstanceOf(VersionTag::class, $tag);
        self::assertNull($tag->version);
        self::assertNull($tag->description);
        self::assertSame('@version', (string) $tag);
    }

    /**
     * @param class-string<VersionedTagInterface> $expected
     */
    #[Test]
    #[DataProvider('versionedTagProvider')]
    public function versionedTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s 1.2.3', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(VersionedTagInterface::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('1.2.3', $tag->version);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<VersionedTagInterface>}>
     */
    public static function versionedTagProvider(): iterable
    {
        yield '@version' => ['version', VersionTag::class];
        yield '@since' => ['since', SinceTag::class];
        yield '@deprecated' => ['deprecated', DeprecatedTag::class];
    }
}
