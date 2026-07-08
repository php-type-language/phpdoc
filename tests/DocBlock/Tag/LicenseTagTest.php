<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\LicenseTag\LicenseTag;

final class LicenseTagTest extends TagTestCase
{
    #[Test]
    public function parsesUrlAndDescription(): void
    {
        $tag = self::parseTag('@license https://opensource.org/licenses/MIT MIT License');

        self::assertInstanceOf(LicenseTag::class, $tag);
        self::assertSame('license', $tag->name);
        self::assertSame('https://opensource.org/licenses/MIT', (string) $tag->url);
        self::assertSame('MIT License', (string) $tag->description);
        self::assertSame('@license https://opensource.org/licenses/MIT MIT License', (string) $tag);
    }

    #[Test]
    public function parsesUrlOnly(): void
    {
        $tag = self::parseTag('@license https://example.com/license');

        self::assertInstanceOf(LicenseTag::class, $tag);
        self::assertSame('https://example.com/license', (string) $tag->url);
        self::assertNull($tag->description);
    }

    #[Test]
    public function parsesBareNameAsDescription(): void
    {
        $tag = self::parseTag('@license MIT Permissive license.');

        self::assertInstanceOf(LicenseTag::class, $tag);
        self::assertNull($tag->url);
        self::assertSame('MIT Permissive license.', (string) $tag->description);
        self::assertSame('@license MIT Permissive license.', (string) $tag);
    }
}
