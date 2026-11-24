<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\SeeTag\SeeTag;
use TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag\TypeAliasTag;
use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Tests\DocBlock\Tag\TagTestCase;

final class TagSpecificationParserTest extends TagTestCase
{
    /**
     * A url with a Cyrillic domain and path, spelled with escapes.
     */
    private const URL = "https://\u{43F}\u{440}\u{438}\u{43C}\u{435}\u{440}.\u{440}\u{444}"
        . "/\u{43F}\u{443}\u{442}\u{44C}";

    /**
     * @return iterable<non-empty-string, array{non-empty-string, non-empty-string}>
     */
    public static function multibyteDescriptionDataProvider(): iterable
    {
        yield 'guillemets after a version' => ['@since 1.0 «quoted»', '«quoted»'];
        yield 'fullwidth parenthesis after a type' => ['@return int （注）', '（注）'];
        yield 'cyrillic url followed by a description' => ['@see ' . self::URL . ' desc', 'desc'];
        yield 'nbsp between a variable and a description' => ["@param int \$a\u{A0}desc", 'desc'];
        yield 'bom between a variable and a description' => ["@param int \$a\u{FEFF}desc", 'desc'];
    }

    /**
     * @param non-empty-string $definition
     * @param non-empty-string $expectedDescription
     */
    #[Test]
    #[DataProvider('multibyteDescriptionDataProvider')]
    public function multibyteTextIsNeverCorrupted(string $definition, string $expectedDescription): void
    {
        $tag = self::parseTag($definition);

        self::assertNotInstanceOf(InvalidTag::class, $tag);
        self::assertNotNull($tag->description);
        self::assertSame($expectedDescription, (string) $tag->description);
        self::assertTrue(\mb_check_encoding((string) $tag, 'UTF-8'));
    }

    #[Test]
    public function nbspEndsTheVariableName(): void
    {
        $tag = self::parseTag("@param int \$a\u{A0}desc");

        self::assertInstanceOf(TypedVariableTag::class, $tag);
        self::assertSame('a', $tag->variable);
    }

    #[Test]
    public function unicodeUrlIsReadAsAWhole(): void
    {
        $tag = self::parseTag('@see ' . self::URL . ' desc');

        self::assertInstanceOf(SeeTag::class, $tag);
        self::assertSame('@see ' . self::URL . ' desc', (string) $tag);
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function unreadTailDataProvider(): iterable
    {
        yield 'unclosed shape' => ['@phpstan-type Foo array{a: int'];
        yield 'unclosed generic' => ['@phpstan-type Foo = array<int, desc'];
        yield 'dangling union' => ['@phpstan-type Foo = int|'];
        yield 'trailing words after check-type' => ['@psalm-check-type $a = int garbage here'];
        yield 'trailing words after import-type' => ['@phpstan-import-type A from B as C trailing junk'];
        yield 'trailing words after flow' => ['@psalm-flow TaintSink $x junk'];
        yield 'trailing words after taint sink' => ['@psalm-taint-sink html $x junk'];
    }

    /**
     * @param non-empty-string $definition
     */
    #[Test]
    #[DataProvider('unreadTailDataProvider')]
    public function unreadTailMakesTheTagInvalid(string $definition): void
    {
        $tag = self::parseTag($definition);

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    #[Test]
    public function trailingWhitespaceIsNotATail(): void
    {
        $tag = self::parseTag("@phpstan-type Foo = int \u{A0} ");

        self::assertInstanceOf(TypeAliasTag::class, $tag);
    }

    #[Test]
    public function tailOfADescriptionGrammarBecomesTheDescription(): void
    {
        $tag = self::parseTag('@since 1.0 some tail here');

        self::assertNotInstanceOf(InvalidTag::class, $tag);
        self::assertNotNull($tag->description);
        self::assertSame('some tail here', (string) $tag->description);
    }
}
