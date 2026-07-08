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
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;

final class IdentifierTagTest extends TestCase
{
    #[Test]
    public function parsesIdentifierWithoutDescription(): void
    {
        $block = new DocBlockParser()->parse('/** @language SQL */');

        self::assertInstanceOf(LanguageTag::class, $block->tags[0]);
        self::assertSame('SQL', $block->tags[0]->identifier);
        self::assertNull($block->tags[0]->description);
        self::assertSame('@language SQL', (string) $block->tags[0]);
    }

    #[Test]
    public function parsesIdentifierWithDescription(): void
    {
        $block = new DocBlockParser()->parse('/** @noinspection PhpUnusedParameterInspection kept for the interface */');

        self::assertInstanceOf(NoinspectionTag::class, $block->tags[0]);
        self::assertSame('PhpUnusedParameterInspection', $block->tags[0]->identifier);
        self::assertSame('kept for the interface', (string) $block->tags[0]->description);
    }

    /**
     * @return iterable<string, array{string, class-string<IdentifierTag>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@psalm-taint-escape' => ['psalm-taint-escape', PsalmTaintEscapeTag::class];
        yield '@psalm-taint-source' => ['psalm-taint-source', PsalmTaintSourceTag::class];
        yield '@psalm-taint-unescape' => ['psalm-taint-unescape', PsalmTaintUnescapeTag::class];
        yield '@language' => ['language', LanguageTag::class];
        yield '@noinspection' => ['noinspection', NoinspectionTag::class];
        yield '@phpcsSuppress' => ['phpcsSuppress', PhpcsSuppressTag::class];
        yield '@codingStandards' => ['codingStandards', CodingStandardsTag::class];
    }

    /**
     * @param class-string<IdentifierTag> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s Foo */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertInstanceOf(IdentifierTag::class, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('Foo', $block->tags[0]->identifier);
    }
}
