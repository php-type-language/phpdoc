<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\PHPDoc\Parser\Comment\CommentParserInterface;
use TypeLang\PHPDoc\Parser\Comment\RegexCommentParser;

#[Group('unit'), Group('type-lang/phpdoc')]
final class CommentParserTest extends TestCase
{
    public static function parserDataProvider(): iterable
    {
        yield RegexCommentParser::class => [new RegexCommentParser()];
    }

    public static function delimiterDataProvider(): array
    {
        return [
            'LF' => ["\n"],
            'CRLF' => ["\r\n"],
        ];
    }

    public static function parserWithVariantDelimitersDataProvider(): iterable
    {
        foreach (self::parserDataProvider() as $parserName => [$provider]) {
            $parserName = (new \ReflectionClass($provider))
                ->getShortName();

            foreach (self::delimiterDataProvider() as $delimiterName => [$delimiter]) {
                yield \basename($parserName) . ' + ' . $delimiterName => [$provider, $delimiter];
            }
        }
    }

    private function convertLineDelimiter(string $docblock, string $delimiter = "\r\n"): string
    {
        return \implode($delimiter, \explode("\n", $docblock));
    }

    private function parseAsArray(CommentParserInterface $parser, string $docblock, string $delimiter = "\n"): array
    {
        if ($delimiter !== "\n") {
            $docblock = $this->convertLineDelimiter($docblock, $delimiter);
        }

        $result = [];

        foreach ($parser->parse($docblock) as $segment) {
            $result[$segment->offset] = $segment->text;
        }

        return $result;
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testWrappedComment(CommentParserInterface $parser, string $delimiter): void
    {
        $docBlock = <<<'PHP'
            /**
             * Line 1
             * Line 2
             */
            PHP;

        self::assertSame(match($delimiter) {
            "\n" => [7 => "Line 1$delimiter", 17 => "Line 2$delimiter"],
            "\r\n" => [8 => "Line 1$delimiter", 19 => "Line 2$delimiter"],
        }, $this->parseAsArray($parser, $docBlock, $delimiter));
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testIncompleteEndingWrappedComment(CommentParserInterface $parser, string $delimiter): void
    {
        $docBlock = <<<'PHP'
            /**
             * Line 1
             *
             * Line 3
            PHP;

        self::assertSame(match($delimiter) {
            "\n" => [7 => "Line 1$delimiter", 20 => "Line 3"],
            "\r\n" => [8 => "Line 1$delimiter", 23 => "Line 3"],
        }, $this->parseAsArray($parser, $docBlock, $delimiter));
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testIncompleteStartingWrappedComment(CommentParserInterface $parser, string $delimiter): void
    {
        $docBlock = <<<'PHP'
             * Line 1
             *
             * Line 3
             */
            PHP;

        self::assertSame(
            expected: [0 => $this->convertLineDelimiter($docBlock, $delimiter)],
            actual: $this->parseAsArray($parser, $docBlock, $delimiter),
        );
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testExtraNonWhitespaceCharsBeforeWrappedComment(CommentParserInterface $parser, string $delimiter): void
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
            actual: $this->parseAsArray($parser, $docBlock, $delimiter),
        );
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testExtraWhitespaceCharsBeforeWrappedComment(CommentParserInterface $parser, string $delimiter): void
    {
        $docBlock = <<<PHP
            \u{0020}\u{000A}
            /**
             * Line 1
             * Line 2
             */
            PHP;

        self::assertSame(match($delimiter) {
            "\n" => [10 => "Line 1$delimiter", 20 => "Line 2$delimiter"],
            "\r\n" => [13 => "Line 1$delimiter", 24 => "Line 2$delimiter"],
        }, $this->parseAsArray($parser, $docBlock, $delimiter));
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testNonWrappedComment(CommentParserInterface $parser, string $delimiter): void
    {
        $docBlock = <<<'PHP'
            Example some
            @phpdoc test
            any test
            @return void Description
            PHP;

        self::assertSame(
            expected: [0 => $this->convertLineDelimiter($docBlock, $delimiter)],
            actual: $this->parseAsArray($parser, $docBlock, $delimiter),
        );
    }

    #[DataProvider('parserWithVariantDelimitersDataProvider')]
    public function testEmptyComment(CommentParserInterface $parser, string $delimiter): void
    {
        self::assertSame([0 => ''], $this->parseAsArray(
            parser: $parser,
            docblock: '',
            delimiter: $delimiter,
        ));
    }
}
