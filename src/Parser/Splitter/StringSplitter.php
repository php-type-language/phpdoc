<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

/**
 * Comment splitter implementation with plain string functions.
 */
final readonly class StringSplitter implements SplitterInterface
{
    /**
     * @var non-empty-string
     */
    private const string OPENING = '/**';

    /**
     * @var non-empty-string
     */
    private const string CLOSING = '*/';

    /**
     * @var non-empty-string
     */
    private const string HORIZONTAL_WHITESPACE = " \t";

    /**
     * @var non-empty-string
     */
    private const string LINE_TERMINATORS = "\r\n";

    /**
     * Characters stripped by {@see \trim()}; a line made up solely of these is
     * blank and therefore not significant.
     *
     * @var non-empty-string
     */
    private const string BLANK = " \t\n\r\0\x0B";

    /**
     * @return iterable<array-key, Segment>
     */
    public function split(string $docblock): iterable
    {
        if ($this->isWrappedComment($docblock)) {
            return $this->readWrappedComment($docblock);
        }

        return [new Segment($docblock)];
    }

    private function isWrappedComment(string $docblock): bool
    {
        if ($docblock === '') {
            return false;
        }

        $offset = \strpos($docblock, self::OPENING);

        // In case of DocBlock:
        return match ($offset) {
            // - Starts with "/**".
            0 => true,
            // - Does not contain "/**" sequence.
            false => false,
            // - Starts with whitespace chars before "/**" sequence.
            default => \ltrim(\substr($docblock, 0, $offset)) === '',
        };
    }

    /**
     * @return list<Segment>
     */
    private function readWrappedComment(string $docblock): array
    {
        $prototype = new Segment();

        $length = \strlen($docblock);
        $offset = 0;
        $atLineStart = true;

        // Position of the next closing sequence, advanced forward only. Keeping
        // it out of the per-line scan turns the whole pass linear instead of
        // rescanning the rest of the string on every content line.
        $closing = \strpos($docblock, self::CLOSING);

        $segments = [];

        // The structural sequences are matched by direct byte comparison rather
        // than substr_compare(): this runs for every line, so avoiding the call
        // overhead matters on large docblocks.
        while ($offset < $length) {
            // Leading indentation only exists at the start of a line; mid-line
            // the cursor already sits on the content.
            $content = $atLineStart
                ? $offset + \strspn($docblock, self::HORIZONTAL_WHITESPACE, $offset)
                : $offset;

            // Comment opening: "/**".
            if ($atLineStart
                && ($content + 2) < $length
                && $docblock[$content] === '/'
                && $docblock[$content + 1] === '*'
                && $docblock[$content + 2] === '*'
            ) {
                $offset = $content + 3;
                $offset += \strspn($docblock, self::HORIZONTAL_WHITESPACE, $offset);
                $atLineStart = false;

                continue;
            }

            // Comment closing: "*/".
            if (($content + 1) < $length
                && $docblock[$content] === '*'
                && $docblock[$content + 1] === '/'
            ) {
                $offset = $content + 2;
                $atLineStart = false;

                continue;
            }

            // Line asterisk prefix: "*".
            if ($atLineStart && $content < $length && $docblock[$content] === '*') {
                $offset = $content + 1;
                $offset += \strspn($docblock, self::HORIZONTAL_WHITESPACE, $offset);
                $atLineStart = false;

                continue;
            }

            // Line terminator.
            $char = $docblock[$offset];

            if ($char === "\n") {
                ++$offset;
                $atLineStart = true;

                continue;
            }

            if ($char === "\r" && ($offset + 1) < $length && $docblock[$offset + 1] === "\n") {
                $offset += 2;
                $atLineStart = true;

                continue;
            }

            // Significant line of content. It starts at $content, past any
            // leading indentation, so the segment text never begins with
            // whitespace even when the line has no "*" prefix; $content also
            // carries the offset forward over the skipped whitespace.
            if ($closing !== false && $closing < $content) {
                $closing = \strpos($docblock, self::CLOSING, $content);
            }

            $end = self::readTextEnd($docblock, $content, $length, $closing);
            $span = $end - $content;

            // Skip blank lines without allocating a trimmed copy of the text.
            if (\strspn($docblock, self::BLANK, $content, $span) !== $span) {
                /** @phpstan-ignore-next-line : Allow readonly mutation */
                $prototype->text = \substr($docblock, $content, $span);
                /** @phpstan-ignore-next-line : Allow readonly mutation */
                $prototype->offset = $content;

                $segments[] = clone $prototype;
            }

            $atLineStart = $docblock[$end - 1] === "\n";
            $offset = $end;
        }

        return $segments;
    }

    /**
     * Finds the end offset of a content line starting at {@see $offset}.
     *
     * A line ends at the comment closing sequence, at the next line terminator
     * (which it includes), or at the end of the string.
     *
     * TODO Should be inlined (?)
     *
     * @param int<0, max> $offset
     * @param int<0, max> $length
     * @param int<0, max>|false $closing offset of the next closing sequence at
     *        or after `$offset`, or {@see false} when there is none
     * @return int<0, max>
     */
    private static function readTextEnd(string $docblock, int $offset, int $length, int|false $closing): int
    {
        // At least the first character always belongs to the line, so the
        // closing sequence only counts beyond it; widen it left across the
        // full "*" run so the line ends where that run begins.
        $from = $offset + 1;

        if ($closing !== false && $closing >= $from) {
            while ($closing > $from && $docblock[$closing - 1] === '*') {
                --$closing;
            }
        } else {
            $closing = false;
        }

        // A lone "\r" is part of the content, so only "\n" and "\r\n" end the
        // line. It ends before the closing sequence when that comes first.
        $cursor = $offset;

        while ($cursor < $length) {
            $cursor += \strcspn($docblock, self::LINE_TERMINATORS, $cursor);

            if ($cursor >= $length) {
                break;
            }

            if ($docblock[$cursor] === "\n") {
                return $closing !== false && $closing < $cursor ? $closing : $cursor + 1;
            }

            if (($cursor + 1) < $length && $docblock[$cursor + 1] === "\n") {
                return $closing !== false && $closing < $cursor ? $closing : $cursor + 2;
            }

            ++$cursor;
        }

        return $closing !== false ? $closing : $length;
    }
}
