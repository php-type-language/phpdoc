<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag\TypeAliasTag;
use TypeLang\Type\NamedTypeNode;

final class TypeAliasTagTest extends TagTestCase
{
    #[Test]
    public function parsesAliasWithExplicitAssignment(): void
    {
        $tag = self::parseTag('@psalm-type MyInt = int');

        self::assertInstanceOf(TypeAliasTag::class, $tag);
        self::assertSame('psalm-type', $tag->name);
        self::assertSame('MyInt', $tag->alias);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('@psalm-type MyInt = int', (string) $tag);
    }

    #[Test]
    public function normalizesOmittedAssignment(): void
    {
        $tag = self::parseTag('@phpstan-type UserId int');

        self::assertInstanceOf(TypeAliasTag::class, $tag);
        self::assertSame('UserId', $tag->alias);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        // The canonical spelling always contains the "=" assignment.
        self::assertSame('@phpstan-type UserId = int', (string) $tag);
    }

    /**
     * @param non-empty-string $name
     */
    #[Test]
    #[DataProvider('typeAliasTagProvider')]
    public function typeAliasTagIsRecognized(string $name): void
    {
        $tag = self::parseTag(\sprintf('@%s Foo = int', $name));

        self::assertInstanceOf(TypeAliasTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('Foo', $tag->alias);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
    }

    /**
     * @return iterable<string, array{non-empty-string}>
     */
    public static function typeAliasTagProvider(): iterable
    {
        yield '@psalm-type' => ['psalm-type'];
        yield '@phpstan-type' => ['phpstan-type'];
        yield '@phan-type' => ['phan-type'];
    }
}
