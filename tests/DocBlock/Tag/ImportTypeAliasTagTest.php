<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\ImportTypeAliasTag\ImportTypeAliasTag;
use TypeLang\Type\NamedTypeNode;

final class ImportTypeAliasTagTest extends TagTestCase
{
    #[Test]
    public function parsesImportWithoutLocalName(): void
    {
        $tag = self::parseTag('@psalm-import-type MyType from SomeClass');

        self::assertInstanceOf(ImportTypeAliasTag::class, $tag);
        self::assertSame('psalm-import-type', $tag->name);
        self::assertSame('MyType', $tag->alias);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertNull($tag->as);
        self::assertSame('@psalm-import-type MyType from SomeClass', (string) $tag);
    }

    #[Test]
    public function parsesImportWithLocalName(): void
    {
        $tag = self::parseTag('@phpstan-import-type MyType from SomeClass as LocalType');

        self::assertInstanceOf(ImportTypeAliasTag::class, $tag);
        self::assertSame('MyType', $tag->alias);
        self::assertSame('LocalType', $tag->as);
        self::assertSame('@phpstan-import-type MyType from SomeClass as LocalType', (string) $tag);
    }

    /**
     * @param non-empty-string $name
     */
    #[Test]
    #[DataProvider('importTypeAliasTagProvider')]
    public function importTypeAliasTagIsRecognized(string $name): void
    {
        $tag = self::parseTag(\sprintf('@%s Foo from Bar as Baz', $name));

        self::assertInstanceOf(ImportTypeAliasTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame('Foo', $tag->alias);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('Baz', $tag->as);
    }

    /**
     * @return iterable<string, array{non-empty-string}>
     */
    public static function importTypeAliasTagProvider(): iterable
    {
        yield '@psalm-import-type' => ['psalm-import-type'];
        yield '@phpstan-import-type' => ['phpstan-import-type'];
    }
}
