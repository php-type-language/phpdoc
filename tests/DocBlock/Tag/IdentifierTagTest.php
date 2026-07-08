<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsTag\CodingStandardsTag;
use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;
use TypeLang\PhpDoc\DocBlock\Tag\LanguageTag\LanguageTag;
use TypeLang\PhpDoc\DocBlock\Tag\NoinspectionTag\NoinspectionTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpcsSuppressTag\PhpcsSuppressTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintEscapeTag\PsalmTaintEscapeTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSourceTag\PsalmTaintSourceTag;
use TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintUnescapeTag\PsalmTaintUnescapeTag;

final class IdentifierTagTest extends TagTestCase
{
    #[Test]
    public function parsesIdentifierWithoutDescription(): void
    {
        $tag = self::parseTag('@language SQL');

        self::assertInstanceOf(LanguageTag::class, $tag);
        self::assertSame('language', $tag->name);
        self::assertSame('SQL', $tag->identifier);
        self::assertNull($tag->description);
        self::assertSame('@language SQL', (string) $tag);
    }

    #[Test]
    public function parsesIdentifierWithDescription(): void
    {
        $tag = self::parseTag('@noinspection PhpUnusedParameterInspection kept for the interface');

        self::assertInstanceOf(NoinspectionTag::class, $tag);
        self::assertSame('PhpUnusedParameterInspection', $tag->identifier);
        self::assertSame('kept for the interface', (string) $tag->description);
        self::assertSame('@noinspection PhpUnusedParameterInspection kept for the interface', (string) $tag);
    }

    /**
     * @param class-string<IdentifierTag> $expected
     */
    #[Test]
    #[DataProvider('identifierTagProvider')]
    public function identifierTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s Foo', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(IdentifierTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('Foo', $tag->identifier);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<IdentifierTag>}>
     */
    public static function identifierTagProvider(): iterable
    {
        yield '@language' => ['language', LanguageTag::class];
        yield '@noinspection' => ['noinspection', NoinspectionTag::class];
        yield '@phpcsSuppress' => ['phpcsSuppress', PhpcsSuppressTag::class];
        yield '@codingStandards' => ['codingStandards', CodingStandardsTag::class];
        yield '@psalm-taint-escape' => ['psalm-taint-escape', PsalmTaintEscapeTag::class];
        yield '@psalm-taint-source' => ['psalm-taint-source', PsalmTaintSourceTag::class];
        yield '@psalm-taint-unescape' => ['psalm-taint-unescape', PsalmTaintUnescapeTag::class];
    }
}
