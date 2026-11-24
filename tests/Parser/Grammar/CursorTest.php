<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser\Grammar;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Tests\TestCase;

final class CursorTest extends TestCase
{
    /**
     * A url with a Cyrillic domain and path, spelled with escapes.
     */
    private const URL = "https://\u{43F}\u{440}\u{438}\u{43C}\u{435}\u{440}.\u{440}\u{444}"
        . "/\u{43F}\u{443}\u{442}\u{44C}";

    #[Test]
    public function startsAtTheBeginning(): void
    {
        $cursor = new Cursor('example');

        self::assertSame(0, $cursor->getPosition());
        self::assertSame(0, $cursor->getOffset());
        self::assertFalse($cursor->isEof());
    }

    #[Test]
    public function rebasesTheOffsetOntoTheSource(): void
    {
        $cursor = new Cursor('example', base: 100);
        $cursor->read(3);

        self::assertSame(3, $cursor->getPosition());
        self::assertSame(103, $cursor->getOffset());
    }

    #[Test]
    public function peeksWithoutConsuming(): void
    {
        $cursor = new Cursor('example');

        self::assertSame('e', $cursor->peek());
        self::assertSame('exam', $cursor->peek(4));
        self::assertSame(0, $cursor->getPosition());
    }

    #[Test]
    public function readsAFixedLength(): void
    {
        $cursor = new Cursor('example');

        self::assertSame('exa', $cursor->read(3));
        self::assertSame('mple', $cursor->read(100));
        self::assertSame('', $cursor->read(1));
        self::assertTrue($cursor->isEof());
    }

    #[Test]
    public function readsWhileTheCharactersMatch(): void
    {
        $cursor = new Cursor('aaabbb');

        self::assertSame('aaa', $cursor->readWhile('a'));
        self::assertSame('', $cursor->readWhile('a'));
        self::assertSame('bbb', $cursor->readWhile('b'));
    }

    #[Test]
    public function readsUntilACharacterMatches(): void
    {
        $cursor = new Cursor('foo=bar');

        self::assertSame('foo', $cursor->readUntil('='));
        self::assertSame('', $cursor->readUntil('='));
        self::assertSame('=bar', $cursor->readRemainder());
    }

    #[Test]
    public function readsWhitespaceDelimitedWords(): void
    {
        $cursor = new Cursor('first second');

        self::assertSame('first', $cursor->readWord());
        $cursor->skipWhitespace();
        self::assertSame('second', $cursor->readWord());
    }

    /**
     * @return iterable<non-empty-string, array{string, string, string}>
     */
    public static function multibyteWordDataProvider(): iterable
    {
        yield 'guillemets' => ['«quoted» rest', '«quoted»', ' rest'];
        yield 'fullwidth parenthesis' => ['（注） rest', '（注）', ' rest'];
        yield 'cyrillic with A0, BB and BF bytes' => [self::URL . ' desc', self::URL, ' desc'];
        yield 'nbsp separator' => ["word\u{A0}rest", 'word', "\u{A0}rest"];
        yield 'bom separator' => ["word\u{FEFF}rest", 'word', "\u{FEFF}rest"];
        yield 'latin character ending with BF byte' => ["\u{FF}x rest", "\u{FF}x", ' rest'];
    }

    #[Test]
    #[DataProvider('multibyteWordDataProvider')]
    public function readsMultibyteWordsWithoutSplittingCharacters(string $input, string $expected, string $rest): void
    {
        $cursor = new Cursor($input);
        $word = $cursor->readWord();

        self::assertSame($expected, $word);
        self::assertTrue(\mb_check_encoding($word, 'UTF-8'));
        self::assertSame($rest, $cursor->readRemainder());
    }

    #[Test]
    public function skipsWholeMultibyteWhitespaceOnly(): void
    {
        $cursor = new Cursor(" \u{A0}\u{FEFF}\t«x»");
        $cursor->skipWhitespace();

        self::assertSame('«x»', $cursor->readRemainder());
    }

    #[Test]
    public function doesNotSkipOtherCharactersSharingWhitespaceBytes(): void
    {
        $cursor = new Cursor('«x» （y）');
        $cursor->skipWhitespace();

        self::assertSame(0, $cursor->getPosition());
        self::assertSame('«x»', $cursor->readWord());
        $cursor->skipWhitespace();
        self::assertSame('（y）', $cursor->readWord());
    }

    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function identifierDataProvider(): iterable
    {
        yield 'simple' => ['foo bar', 'foo', ' bar'];
        yield 'underscore' => ['_foo123!', '_foo123', '!'];
        yield 'unicode' => ['переменная rest', 'переменная', ' rest'];
        yield 'stops at namespace' => ['Foo\\Bar', 'Foo', '\\Bar'];
        yield 'none' => ['!nope', '', '!nope'];
    }

    #[Test]
    #[DataProvider('identifierDataProvider')]
    public function readsAnIdentifier(string $input, string $expected, string $rest): void
    {
        $cursor = new Cursor($input);

        self::assertSame($expected, $cursor->readPhpIdentifier());
        self::assertSame($rest, $cursor->readRemainder());
    }

    #[Test]
    public function readsAQualifiedName(): void
    {
        $cursor = new Cursor('Some\\Any\\Класс::member');

        self::assertSame('Some\\Any\\Класс', $cursor->readPhpQualifiedName());
        self::assertSame('::member', $cursor->readRemainder());
    }

    #[Test]
    public function consumesAMatchingLiteral(): void
    {
        $cursor = new Cursor('$name');

        self::assertTrue($cursor->readLiteral('$'));
        self::assertSame(1, $cursor->getPosition());
    }

    #[Test]
    public function leavesTheCursorOnAMismatchingLiteral(): void
    {
        $cursor = new Cursor('name');

        self::assertFalse($cursor->readLiteral('$'));
        self::assertSame(0, $cursor->getPosition());
    }

    #[Test]
    public function doesNotMatchALiteralPastTheEnd(): void
    {
        $cursor = new Cursor('ab');

        self::assertFalse($cursor->readLiteral('abc'));
        self::assertSame(0, $cursor->getPosition());
    }

    #[Test]
    public function remembersTheFurthestOffset(): void
    {
        $cursor = new Cursor('example', base: 10);
        $cursor->read(5);
        $cursor->moveTo(2);

        self::assertSame(12, $cursor->getOffset());
        self::assertSame(15, $cursor->getFurthestOffset());
    }
}
