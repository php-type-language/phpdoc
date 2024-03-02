<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit\DocBlock;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\PHPDoc\Parser\Comment\LexerAwareCommentParser;

#[Group('unit'), Group('type-lang/phpdoc')]
final class CommentReaderTest extends DocBlockTestCase
{
    private LexerAwareCommentParser $reader;

    #[Before]
    public function bootCommentReader(): void
    {
        $this->reader = new LexerAwareCommentParser();
    }

    /**
     * @template TArgKey of int<0, max>
     * @template TArgValue of string
     *
     * @param iterable<TArgKey, TArgValue> $lines
     *
     * @return array<TArgKey, TArgValue>
     */
    private function toArray(iterable $lines): array
    {
        if (\is_array($lines)) {
            return $lines;
        }

        return \iterator_to_array($lines, true);
    }

    private function convertLineDelimiter(string $docblock, string $delimiter = "\r\n"): string
    {
        return \implode($delimiter, \explode("\n", $docblock));
    }

    private function readAsArray(string $docblock, string $delimiter = "\n"): array
    {
        if ($delimiter !== "\n") {
            $docblock = $this->convertLineDelimiter($docblock, $delimiter);
        }

        return $this->toArray(
            lines: $this->reader->parse($docblock),
        );
    }

    public static function delimiterDataProvider(): array
    {
        return [
            'LF' => ["\n"],
            'CRLF' => ["\r\n"],
        ];
    }

    #[DataProvider('delimiterDataProvider')]
    public function testWrappedComment(string $delimiter): void
    {
        $docBlock = <<<'PHP'
            /**
             * Line 1
             * Line 2
             */
            PHP;

        self::assertSame([
            $delimiter === "\n" ? 7 : 8 => 'Line 1',
            $delimiter === "\n" ? 17 : 19 => 'Line 2',
        ], $this->readAsArray($docBlock, $delimiter));
    }

    #[DataProvider('delimiterDataProvider')]
    public function testIncompleteEndingWrappedComment(string $delimiter): void
    {
        $docBlock = <<<'PHP'
            /**
             * Line 1
             *
             * Line 3
            PHP;

        self::assertSame([
            $delimiter === "\n" ? 7 : 8 => 'Line 1',
            $delimiter === "\n" ? 20 : 23 => 'Line 3',
        ], $this->readAsArray($docBlock, $delimiter));
    }

    #[DataProvider('delimiterDataProvider')]
    public function testIncompleteStartingWrappedComment(string $delimiter): void
    {
        $docBlock = <<<'PHP'
             * Line 1
             *
             * Line 3
             */
            PHP;

        self::assertSame(
            expected: [0 => $this->convertLineDelimiter($docBlock, $delimiter)],
            actual: $this->readAsArray($docBlock, $delimiter),
        );
    }

    #[DataProvider('delimiterDataProvider')]
    public function testExtraNonWhitespaceCharsBeforeWrappedComment(string $delimiter): void
    {
        $docBlock = <<<'PHP'
            Some unrecognized chars
            /**
             * Line 1
             *
             * Line 3
             */
            PHP;

        self::assertSame(
            expected: [0 => $this->convertLineDelimiter($docBlock, $delimiter)],
            actual: $this->readAsArray($docBlock, $delimiter),
        );
    }

    #[DataProvider('delimiterDataProvider')]
    public function testExtraWhitespaceCharsBeforeWrappedComment(string $delimiter): void
    {
        $docBlock = <<<PHP
            \u{0020}\u{000A}
            /**
             * Line 1
             * Line 2
             */
            PHP;

        self::assertSame([
            $delimiter === "\n" ? 10 : 13 => 'Line 1',
            $delimiter === "\n" ? 20 : 24 => 'Line 2',
        ], $this->readAsArray($docBlock, $delimiter));
    }

    #[DataProvider('delimiterDataProvider')]
    public function testNonWrappedComment(string $delimiter): void
    {
        $docBlock = <<<'PHP'
            Example some
            @phpdoc test
            any test
            @return void Description
            PHP;

        self::assertSame(
            expected: [0 => $this->convertLineDelimiter($docBlock, $delimiter)],
            actual: $this->readAsArray($docBlock, $delimiter),
        );
    }
}
