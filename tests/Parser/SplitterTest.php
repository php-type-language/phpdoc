<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\Parser\Splitter\Segment;
use TypeLang\PhpDoc\Parser\Splitter\SplitterInterface;
use TypeLang\PhpDoc\Parser\Splitter\StringSplitter;
use TypeLang\PhpDoc\Tests\TestCase;

final class SplitterTest extends TestCase
{
    /**
     * @return iterable<string, array{SplitterInterface}>
     */
    public static function splitterDataProvider(): iterable
    {
        yield 'StringSplitter' => [new StringSplitter()];
    }

    /**
     * @return iterable<string, array{SplitterInterface, string}>
     */
    public static function unwrappedInputsDataProvider(): iterable
    {
        $inputs = [
            'plain text' => 'Just some text',
            'opening not at start' => 'abc /** x */',
        ];

        foreach (self::splitterDataProvider() as $splitterName => [$splitter]) {
            foreach ($inputs as $inputName => $input) {
                yield $splitterName . ': ' . $inputName => [$splitter, $input];
            }
        }
    }

    /**
     * @return iterable<string, array{SplitterInterface, string}>
     */
    public static function blankInputsDataProvider(): iterable
    {
        $inputs = [
            'empty string' => '',
            'whitespace only' => '    ',
        ];

        foreach (self::splitterDataProvider() as $splitterName => [$splitter]) {
            foreach ($inputs as $inputName => $input) {
                yield $splitterName . ': ' . $inputName => [$splitter, $input];
            }
        }
    }

    /**
     * @return iterable<string, array{SplitterInterface, string}>
     */
    public static function wrappedInputsDataProvider(): iterable
    {
        $inputs = [
            'multiline description' => self::comment(
                '/**',
                ' * Example line 1',
                ' *',
                ' * @tag1 type Description of tag1',
                ' */',
            ),
            'single line' => '/** Foo bar */',
            'leading whitespace before opening' => "   \n" . self::comment(
                '/**',
                ' * Hi',
                ' */',
            ),
            'multiline tag' => self::comment(
                '/**',
                ' * @param int $a first',
                ' *   second line',
                ' * @return void',
                ' */',
            ),
            'body without star prefix' => self::comment(
                '/**',
                'Plain',
                '*/',
            ),
        ];

        foreach (self::splitterDataProvider() as $splitterName => [$splitter]) {
            foreach ($inputs as $inputName => $input) {
                yield $splitterName . ': ' . $inputName => [$splitter, $input];
            }
        }
    }

    /**
     * @return iterable<string, array{SplitterInterface, string, list<array{string, int<0, max>}>}>
     */
    public static function lineEndingCasesDataProvider(): iterable
    {
        $cases = [
            'LF line endings' => [
                "/**\n * a\n * b\n */",
                [["a\n", 7], ["b\n", 12]],
            ],
            'CRLF line endings' => [
                "/**\r\n * a\r\n * b\r\n */",
                [["a\r\n", 8], ["b\r\n", 14]],
            ],
            'CRLF tag line' => [
                "/**\r\n * @param int \$x d\r\n */",
                [["@param int \$x d\r\n", 8]],
            ],
            'mixed LF and CRLF' => [
                "/**\r\n * a\n * b\r\n */",
                [["a\n", 8], ["b\r\n", 13]],
            ],
            'LF indented tag without star' => [
                "/**\n @return x\n */",
                [["@return x\n", 5]],
            ],
            'LF tab-indented without star' => [
                "/**\n\t@tag y\n */",
                [["@tag y\n", 5]],
            ],
            'CRLF indented without star' => [
                "/**\r\n  plain\r\n */",
                [["plain\r\n", 7]],
            ],
        ];

        foreach (self::splitterDataProvider() as $splitterName => [$splitter]) {
            foreach ($cases as $caseName => [$input, $expected]) {
                yield $splitterName . ': ' . $caseName => [$splitter, $input, $expected];
            }
        }
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function parseReturnsIterable(SplitterInterface $parser): void
    {
        self::assertIsIterable($parser->split('/** example */'));
    }

    #[Test]
    #[DataProvider('wrappedInputsDataProvider')]
    public function parseYieldsOnlySegmentInstances(SplitterInterface $parser, string $input): void
    {
        self::assertContainsOnlyInstancesOf(Segment::class, self::segments($parser->split($input)));
    }

    #[Test]
    #[DataProvider('unwrappedInputsDataProvider')]
    public function unwrappedInputBecomesSingleSegment(SplitterInterface $parser, string $input): void
    {
        $segments = self::segments($parser->split($input));

        self::assertCount(1, $segments);
        self::assertSame($input, $segments[0]->text);
        self::assertSame(0, $segments[0]->offset);
    }

    #[Test]
    #[DataProvider('blankInputsDataProvider')]
    public function blankInputYieldsNoSegments(SplitterInterface $parser, string $input): void
    {
        self::assertCount(0, self::segments($parser->split($input)));
    }

    #[Test]
    #[DataProvider('wrappedInputsDataProvider')]
    public function segmentOffsetPointsToItsTextInSource(SplitterInterface $parser, string $input): void
    {
        // Offsets index the normalized docblock (leading and trailing blanks
        // stripped), so the invariant is checked against the same string.
        $source = \trim($input, " \t\n\r\0\x0B");

        foreach (self::segments($parser->split($input)) as $segment) {
            self::assertSame(
                \substr($source, $segment->offset, \strlen($segment->text)),
                $segment->text,
                'A segment text must be a verbatim slice of the source at its offset',
            );
        }
    }

    #[Test]
    #[DataProvider('wrappedInputsDataProvider')]
    public function segmentsAreReturnedInSourceOrder(SplitterInterface $parser, string $input): void
    {
        $previous = -1;

        foreach (self::segments($parser->split($input)) as $segment) {
            self::assertGreaterThan($previous, $segment->offset);
            $previous = $segment->offset;
        }
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function wrappedCommentYieldsSignificantLinesInOrder(SplitterInterface $parser): void
    {
        $input = self::comment(
            '/**',
            ' * Example line 1',
            ' *',
            ' * @tag1 type Description of tag1',
            ' */',
        );

        self::assertSame(
            ['Example line 1', '@tag1 type Description of tag1'],
            self::trimmedTexts($parser->split($input)),
        );
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function blankCommentLinesAreSkipped(SplitterInterface $parser): void
    {
        $input = self::comment(
            '/**',
            ' * first',
            ' *',
            ' *   ',
            ' * second',
            ' */',
        );

        self::assertSame(['first', 'second'], self::trimmedTexts($parser->split($input)));
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function singleLineCommentYieldsSingleSegment(SplitterInterface $parser): void
    {
        self::assertSame(['Foo bar'], self::trimmedTexts($parser->split('/** Foo bar */')));
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function leadingWhitespaceBeforeOpeningIsTreatedAsComment(SplitterInterface $parser): void
    {
        $input = "   \n" . self::comment(
            '/**',
            ' * Hi',
            ' */',
        );

        self::assertSame(['Hi'], self::trimmedTexts($parser->split($input)));
    }

    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function tagContinuationLinesBecomeSeparateSegments(SplitterInterface $parser): void
    {
        $input = self::comment(
            '/**',
            ' * @param int $a first',
            ' *   second line',
            ' * @return void',
            ' */',
        );

        self::assertSame(
            ['@param int $a first', 'second line', '@return void'],
            self::trimmedTexts($parser->split($input)),
        );
    }

    /**
     * A line without a leading "*" prefix still has its indentation stripped,
     * so the segment text never begins with whitespace and its offset points
     * at the first significant character.
     */
    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function leadingWhitespaceIsStrippedWhenLineHasNoStarPrefix(SplitterInterface $parser): void
    {
        // "/**\n @return example\n */" — note the space before "@", and no "*".
        $input = self::comment('/**', ' @return example', ' */');

        $segments = self::segments($parser->split($input));

        self::assertCount(1, $segments);
        self::assertSame("@return example\n", $segments[0]->text);
        // The "@" sits at index 5: "/**"(3) + "\n"(1) + " "(1).
        self::assertSame(5, $segments[0]->offset);
        self::assertSame(
            \substr($input, $segments[0]->offset, \strlen($segments[0]->text)),
            $segments[0]->text,
            'A stripped segment must still be a verbatim slice of the source at its offset',
        );
    }

    /**
     * Whether or not a line carries a "*" prefix, the resulting segment text is
     * the same; only the offset differs (by the width of the skipped prefix).
     */
    #[Test]
    #[DataProvider('splitterDataProvider')]
    public function starlessLineYieldsSameTextAsStarPrefixedLine(SplitterInterface $parser): void
    {
        $texts = static fn(iterable $result): array => \array_map(
            static fn(Segment $segment): string => $segment->text,
            self::segments($result),
        );

        self::assertSame(
            $texts($parser->split(self::comment('/**', ' * @var int $x', ' */'))),
            $texts($parser->split(self::comment('/**', ' @var int $x', ' */'))),
        );
    }

    /**
     * The text of every segment retains its own trailing line terminator
     * verbatim (`\n` or `\r\n`), and offsets are byte offsets, so a `\r\n`
     * line shifts subsequent offsets by the extra carriage return byte.
     *
     * @param list<array{string, int<0, max>}> $expected
     */
    #[Test]
    #[DataProvider('lineEndingCasesDataProvider')]
    public function preservesLineEndingsVerbatim(SplitterInterface $parser, string $input, array $expected): void
    {
        $segments = self::segments($parser->split($input));

        self::assertSame(\array_column($expected, 0), \array_map(
            static fn(Segment $segment): string => $segment->text,
            $segments,
        ));

        self::assertSame(\array_column($expected, 1), \array_map(
            static fn(Segment $segment): int => $segment->offset,
            $segments,
        ));
    }

    /**
     * @return iterable<string, SplitterInterface>
     */
    private static function createSplitters(): iterable
    {
        yield 'StringSplitter' => new StringSplitter();
    }

    /**
     * Builds a comment source from the given lines using `\n` separators.
     */
    private static function comment(string ...$lines): string
    {
        return \implode("\n", $lines);
    }

    /**
     * Normalizes the {@see SplitterInterface::split()} result (which may be
     * any iterable) into a positionally indexed list of segments.
     *
     * @param iterable<Segment> $result
     * @return list<Segment>
     */
    private static function segments(iterable $result): array
    {
        return \iterator_to_array($result, false);
    }

    /**
     * @return list<string>
     */
    private static function trimmedTexts(iterable $result): array
    {
        $transform = static fn(Segment $segment): string => \trim($segment->text);

        /** @var list<string> */
        return \array_map($transform, self::segments($result));
    }
}
