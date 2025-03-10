<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit\Parser\Comment;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PHPDoc\Parser\Comment\CommentParserInterface;
use TypeLang\PHPDoc\Tests\Unit\Parser\ParserTestCase;

abstract class CommentParserTestCase extends ParserTestCase
{
    abstract public static function getCommentParser(): CommentParserInterface;

    public static function delimiterDataProvider(): array
    {
        return [
            'LF' => ["\n"],
            'CRLF' => ["\r\n"],
        ];
    }

    private function convertLineDelimiter(string $docblock, string $delimiter = "\r\n"): string
    {
        return \implode($delimiter, \explode("\n", $docblock));
    }

    private function parseAsArray(string $docblock, string $delimiter = "\n"): array
    {
        if ($delimiter !== "\n") {
            $docblock = $this->convertLineDelimiter($docblock, $delimiter);
        }

        $result = [];

        $parser = static::getCommentParser();

        foreach ($parser->parse($docblock) as $segment) {
            $result[$segment->offset] = $segment->text;
        }

        return $result;
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

        self::assertSame(
            match ($delimiter) {
                "\n" => [7 => "Line 1$delimiter", 17 => "Line 2$delimiter"],
                "\r\n" => [8 => "Line 1$delimiter", 19 => "Line 2$delimiter"],
            },
            $this->parseAsArray($docBlock, $delimiter)
        );
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

        self::assertSame(
            match ($delimiter) {
                "\n" => [7 => "Line 1$delimiter", 20 => 'Line 3'],
                "\r\n" => [8 => "Line 1$delimiter", 23 => 'Line 3'],
            },
            $this->parseAsArray($docBlock, $delimiter)
        );
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
            actual: $this->parseAsArray($docBlock, $delimiter),
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
            actual: $this->parseAsArray($docBlock, $delimiter),
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

        self::assertSame(
            match ($delimiter) {
                "\n" => [10 => "Line 1$delimiter", 20 => "Line 2$delimiter"],
                "\r\n" => [13 => "Line 1$delimiter", 24 => "Line 2$delimiter"],
            },
            $this->parseAsArray($docBlock, $delimiter)
        );
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
            actual: $this->parseAsArray($docBlock, $delimiter),
        );
    }

    #[DataProvider('delimiterDataProvider')]
    public function testEmptyComment(string $delimiter): void
    {
        self::assertSame([0 => ''], $this->parseAsArray(
            docblock: '',
            delimiter: $delimiter,
        ));
    }
}
