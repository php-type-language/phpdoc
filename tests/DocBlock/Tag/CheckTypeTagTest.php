<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\CheckTypeTag;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\PsalmCheckTypeExactTag;
use TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag\PsalmCheckTypeTag;
use TypeLang\Type\NamedTypeNode;

final class CheckTypeTagTest extends TagTestCase
{
    #[Test]
    public function parsesVariableAndType(): void
    {
        $tag = self::parseTag('@psalm-check-type $foo = int');

        self::assertInstanceOf(PsalmCheckTypeTag::class, $tag);
        self::assertSame('psalm-check-type', $tag->name);
        self::assertSame('foo', $tag->variable);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('@psalm-check-type $foo = int', (string) $tag);
    }

    #[Test]
    public function exactVariantIsADistinctClass(): void
    {
        $tag = self::parseTag('@psalm-check-type-exact $bar = int');

        self::assertInstanceOf(PsalmCheckTypeExactTag::class, $tag);
        self::assertSame('@psalm-check-type-exact $bar = int', (string) $tag);
    }

    /**
     * @param class-string<CheckTypeTag> $expected
     */
    #[Test]
    #[DataProvider('checkTypeTagProvider')]
    public function checkTypeTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s $x = int', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(CheckTypeTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('x', $tag->variable);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<CheckTypeTag>}>
     */
    public static function checkTypeTagProvider(): iterable
    {
        yield '@psalm-check-type' => ['psalm-check-type', PsalmCheckTypeTag::class];
        yield '@psalm-check-type-exact' => ['psalm-check-type-exact', PsalmCheckTypeExactTag::class];
    }
}
