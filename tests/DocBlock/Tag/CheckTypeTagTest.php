<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\CheckTypeTag;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\PsalmCheckTypeExactTag;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\PsalmCheckTypeTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class CheckTypeTagTest extends TestCase
{
    #[Test]
    public function parsesVariableAndType(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-check-type $foo = int */');

        self::assertInstanceOf(PsalmCheckTypeTag::class, $block->tags[0]);
        self::assertSame('psalm-check-type', $block->tags[0]->name);
        self::assertSame('foo', $block->tags[0]->variable);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
        self::assertSame('@psalm-check-type $foo = int', (string) $block->tags[0]);
    }

    #[Test]
    public function exactVariantIsADistinctClass(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-check-type-exact $bar = int */');

        self::assertInstanceOf(PsalmCheckTypeExactTag::class, $block->tags[0]);
        self::assertSame('@psalm-check-type-exact $bar = int', (string) $block->tags[0]);
    }

    /**
     * @return iterable<string, array{string, class-string<CheckTypeTag>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@psalm-check-type' => ['psalm-check-type', PsalmCheckTypeTag::class];
        yield '@psalm-check-type-exact' => ['psalm-check-type-exact', PsalmCheckTypeExactTag::class];
    }

    /**
     * @param class-string<CheckTypeTag> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s $x = int */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertInstanceOf(CheckTypeTag::class, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('x', $block->tags[0]->variable);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
    }
}
