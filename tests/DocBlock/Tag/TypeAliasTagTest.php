<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag\TypeAliasTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class TypeAliasTagTest extends TestCase
{
    #[Test]
    public function parsesAliasWithExplicitAssignment(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-type MyInt = int */');

        self::assertInstanceOf(TypeAliasTag::class, $block->tags[0]);
        self::assertSame('psalm-type', $block->tags[0]->name);
        self::assertSame('MyInt', $block->tags[0]->alias);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
        self::assertSame('@psalm-type MyInt = int', (string) $block->tags[0]);
    }

    #[Test]
    public function parsesAliasWithoutAssignment(): void
    {
        $block = new DocBlockParser()->parse('/** @phpstan-type UserId int */');

        self::assertInstanceOf(TypeAliasTag::class, $block->tags[0]);
        self::assertSame('UserId', $block->tags[0]->alias);
        self::assertSame('@phpstan-type UserId = int', (string) $block->tags[0]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function tagProvider(): iterable
    {
        yield '@psalm-type' => ['psalm-type'];
        yield '@phpstan-type' => ['phpstan-type'];
        yield '@phan-type' => ['phan-type'];
    }

    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s Foo = int */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(TypeAliasTag::class, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('Foo', $block->tags[0]->alias);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
    }
}
