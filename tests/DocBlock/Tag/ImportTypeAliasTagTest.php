<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\ImportTypeAliasTag\ImportTypeAliasTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class ImportTypeAliasTagTest extends TestCase
{
    #[Test]
    public function parsesImportWithoutLocalName(): void
    {
        $block = new DocBlockParser()->parse('/** @psalm-import-type MyType from SomeClass */');

        self::assertInstanceOf(ImportTypeAliasTag::class, $block->tags[0]);
        self::assertSame('psalm-import-type', $block->tags[0]->name);
        self::assertSame('MyType', $block->tags[0]->alias);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
        self::assertNull($block->tags[0]->as);
        self::assertSame('@psalm-import-type MyType from SomeClass', (string) $block->tags[0]);
    }

    #[Test]
    public function parsesImportWithLocalName(): void
    {
        $block = new DocBlockParser()->parse('/** @phpstan-import-type MyType from SomeClass as LocalType */');

        self::assertInstanceOf(ImportTypeAliasTag::class, $block->tags[0]);
        self::assertSame('MyType', $block->tags[0]->alias);
        self::assertSame('LocalType', $block->tags[0]->as);
        self::assertSame('@phpstan-import-type MyType from SomeClass as LocalType', (string) $block->tags[0]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function tagProvider(): iterable
    {
        yield '@psalm-import-type' => ['psalm-import-type'];
        yield '@phpstan-import-type' => ['phpstan-import-type'];
    }

    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s Foo from Bar as Baz */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(ImportTypeAliasTag::class, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame('Foo', $block->tags[0]->alias);
        self::assertInstanceOf(NamedTypeNode::class, $block->tags[0]->type);
        self::assertSame('Baz', $block->tags[0]->as);
    }
}
