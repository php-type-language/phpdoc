<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

final readonly class StringSplitter implements SplitterInterface
{
    /**
     * @var non-empty-string
     */
    private const string SEQUENCE_OPENING = '/*';

    /**
     * @var non-empty-string
     */
    private const string SEQUENCE_OPENING_DOCBLOCK = '/**';

    /**
     * @var non-empty-string
     */
    private const string SEQUENCE_CLOSING = '*/';

    /**
     * @var non-empty-string
     */
    private const string SEQUENCE_BODY = '*';

    /**
     * @var non-empty-string
     */
    private const string LINE_TERMINATORS = "\r\n";

    /**
     * Characters trimmed from the head of a line before its content begins.
     *
     * @var non-empty-string
     */
    private const string BLANK = " \t\n\r\0\x0B";

    /**
     * Trailing whitespace trimmed from a line: {@see BLANK} without the line
     * terminators, which delimit lines rather than pad them.
     *
     * @var non-empty-string
     */
    private const string TRAILING_WHITESPACE = " \t\0\x0B";

    /**
     * @return list<Segment>
     */
    public function split(string $docblock): array
    {
        $docblock = \trim($docblock, self::BLANK);

        return match (true) {
            $docblock === '' => [],
            \str_starts_with($docblock, self::SEQUENCE_OPENING_DOCBLOCK)
                => self::readComment($docblock, \strlen(self::SEQUENCE_OPENING_DOCBLOCK)),
            \str_starts_with($docblock, self::SEQUENCE_OPENING)
                => self::readComment($docblock, \strlen(self::SEQUENCE_OPENING)),
            default => [new Segment($docblock)],
        };
    }

    /**
     * Reads a wrapped comment line by line, starting past its opening
     * sequence at {@see $offset}.
     *
     * @param int<0, max> $offset
     * @return list<Segment>
     */
    private static function readComment(string $docblock, int $offset): array
    {
        /** @phpstan-ignore-next-line : Pre-allocate (invalid) segment in order to use it as a
         *                              template in the future (speeding up object instantiation) */
        $prototype = new Segment('');

        $length = \strlen($docblock);

        // Content ends at the closing "*/"; nothing from there on counts.
        $closing = \strpos($docblock, self::SEQUENCE_CLOSING, $offset);
        $end = $closing === false ? $length : $closing;

        $segments = [];

        while ($offset < $end) {
            $break = $offset + \strcspn($docblock, self::LINE_TERMINATORS, $offset);
            $lineEnd = \min($break, $end);

            $from = self::lineStartsAt($docblock, $offset, $lineEnd);
            $content = \rtrim(\substr($docblock, $from, $lineEnd - $from), self::TRAILING_WHITESPACE);

            // Move past the terminator ("\n", "\r" or "\r\n") ending the line.
            $offset = $break;

            if ($break < $length) {
                $offset += $docblock[$break] === "\r" && ($docblock[$break + 1] ?? '') === "\n" ? 2 : 1;
            }

            // Blank lines are dropped; a significant one keeps its terminator.
            if ($content !== '') {
                $terminator = $break < $end ? \substr($docblock, $break, $offset - $break) : '';

                /** @phpstan-ignore-next-line : Allow external mutation */
                $prototype->text = $content . $terminator;
                /** @phpstan-ignore-next-line : Allow external mutation */
                $prototype->offset = $from;

                $segments[] = clone $prototype;
            }
        }

        return $segments;
    }

    /**
     * Returns offset of the comment's line start
     *
     * @param int<0, max> $offset
     * @param int<0, max> $lineEnd
     * @return int<0, max>
     */
    private static function lineStartsAt(string $docblock, int $offset, int $lineEnd): int
    {
        // Skip starting whitespaces (e.g. " * @tag" -> "* @tag")
        $offset += \strspn($docblock, self::BLANK, $offset, $lineEnd - $offset);

        // Skip "star" char (e.g. "* @tag" -> " @tag")
        if ($offset < $lineEnd && $docblock[$offset] === self::SEQUENCE_BODY) {
            ++$offset;

            // Skip other whitespaces (e.g. " @tag" -> "@tag")
            //
            // TBD This behavior seems to make it impossible to use indents for
            // markdown, and only the leading space needs to be removed.
            // However, this behavior makes description parsing more... predictable (?)
            $offset += \strspn($docblock, self::BLANK, $offset, $lineEnd - $offset);
        }

        return $offset;
    }
}
