<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar;

/**
 * A reading position over a tag suffix that grammar rules consume from.
 *
 * The reading methods cover the shapes a rule usually needs (a word, an
 * identifier, a literal, a run of characters), so a rule rarely has to call
 * {@see moveTo()} by hand that is reserved for rewinding a speculative match.
 */
final class Cursor
{
    /**
     * The single-byte whitespace characters that separate words.
     */
    private const CHARS_WHITESPACE = " \t\n\r\0\x0B\x0C";

    /**
     * The lead bytes of the multibyte whitespace characters.
     */
    private const CHARS_WHITESPACE_LEAD = "\xC2\xEF";

    /**
     * The multibyte whitespace characters: NBSP (U+00A0) and BOM (U+FEFF).
     */
    private const SEQUENCES_WHITESPACE = ["\u{A0}", "\u{FEFF}"];

    /**
     * @var int<0, max>
     */
    private readonly int $length;

    /**
     * The furthest position ever reached, reported by
     * {@see getFurthestOffset()} when a match fails.
     *
     * @var int<0, max>
     */
    private int $furthest = 0;

    /**
     * The current position within the buffer.
     *
     * Reading advances it, {@see moveTo()} rewinds it to roll a speculative
     * match back.
     *
     * @var int<0, max>
     */
    private int $current = 0;

    /**
     * List of PHP identifier chars as string
     *
     * @var non-empty-string
     */
    private static string $phpIdentifierChars;

    public function __construct(
        private readonly string $buffer,
        /**
         * Byte offset of the buffer inside the source.
         *
         * @var int<0, max>
         */
        public readonly int $base = 0,
    ) {
        $this->length = \strlen($buffer);

        self::$phpIdentifierChars ??= self::createIdentifierChars();
    }

    /**
     * The current position within the buffer.
     *
     * @return int<0, max>
     */
    public function getPosition(): int
    {
        return $this->current;
    }

    /**
     * Rewinds the cursor to roll a speculative match back. Whatever it is set
     * to, the furthest position reached is remembered for failure reporting.
     *
     * @param int<0, max> $position
     */
    public function moveTo(int $position): void
    {
        $this->current = $position;

        if ($position > $this->furthest) {
            $this->furthest = $position;
        }
    }

    /**
     * The current byte offset within the source.
     *
     * @return int<0, max>
     */
    public function getOffset(): int
    {
        return $this->base + $this->current;
    }

    /**
     * The byte offset to report when a match fails.
     *
     * @return int<0, max>
     */
    public function getFurthestOffset(): int
    {
        return $this->base + $this->furthest;
    }

    public function isEof(): bool
    {
        return $this->current >= $this->length;
    }

    /**
     * Returns the upcoming bytes without consuming them.
     */
    public function peek(int $length = 1): string
    {
        return \substr($this->buffer, $this->current, \max(0, $length));
    }

    /**
     * Consumes and returns up to $length bytes.
     */
    public function read(int $length): string
    {
        $value = \substr($this->buffer, $this->current, \max(0, $length));

        $this->moveTo($this->current + \strlen($value));

        return $value;
    }

    /**
     * Consumes and returns the leading run of $characters, or an empty string
     * when the current byte is not one of them.
     */
    public function readWhile(string $characters): string
    {
        return $this->read(\strspn($this->buffer, $characters, $this->current));
    }

    /**
     * Consumes and returns everything up to (but not including) the first of
     * $characters, or an empty string when the current byte is already one of
     * them.
     */
    public function readUntil(string $characters): string
    {
        return $this->read(\strcspn($this->buffer, $characters, $this->current));
    }

    /**
     * Consumes and returns the next whitespace-delimited word, or an empty
     * string when none is left.
     */
    public function readWord(): string
    {
        return $this->readRun(self::CHARS_WHITESPACE, isStopSet: true);
    }

    /**
     * Consumes and returns a name (letters, digits, "_" and any multibyte
     * character), or an empty string when the current byte starts none.
     */
    public function readPhpIdentifier(): string
    {
        return $this->readRun(self::$phpIdentifierChars, isStopSet: false);
    }

    /**
     * Consumes and returns a namespaced name (a {@see readPhpIdentifier()} that
     * may also contain "\"), or an empty string when the current byte starts
     * none.
     */
    public function readPhpQualifiedName(): string
    {
        return $this->readRun('\\' . self::$phpIdentifierChars, isStopSet: false);
    }

    /**
     * Consumes and returns the leading run of the given `$characters` (or, with
     * `$isStopSet`, of everything but them). A multibyte whitespace character
     * ends the run, any other multibyte character is never split.
     *
     * The `$characters` mask must not contain the lead bytes of multibyte
     * whitespace.
     */
    private function readRun(string $characters, bool $isStopSet): string
    {
        $end = $this->current;

        while (true) {
            $end += match ($isStopSet) {
                true => \strcspn($this->buffer, $characters . self::CHARS_WHITESPACE_LEAD, $end),
                false => \strspn($this->buffer, $characters, $end),
            };

            if ($end >= $this->length
                || !self::isWhitespaceLead($this->buffer[$end])
                || $this->measureWhitespace($end) > 0
            ) {
                break;
            }

            // The lead byte of some other multibyte character.
            ++$end;
        }

        return $this->read($end - $this->current);
    }

    private static function isWhitespaceLead(string $byte): bool
    {
        return \strpos(self::CHARS_WHITESPACE_LEAD, $byte) !== false;
    }

    /**
     * Consumes $literal when it appears at the current position and reports
     * whether it did, leaving the cursor untouched otherwise.
     */
    public function readLiteral(string $literal): bool
    {
        $length = \strlen($literal);

        if ($length === 0) {
            return true;
        }

        if ($this->current + $length > $this->length
            || \substr_compare($this->buffer, $literal, $this->current, $length) !== 0
        ) {
            return false;
        }

        $this->moveTo($this->current + $length);

        return true;
    }

    /**
     * Advances over any leading whitespace.
     */
    public function skipWhitespace(): void
    {
        $end = $this->current;

        while (true) {
            $end += \strspn($this->buffer, self::CHARS_WHITESPACE, $end);

            $size = $this->measureWhitespace($end);

            if ($size === 0) {
                break;
            }

            $end += $size;
        }

        $this->moveTo($end);
    }

    /**
     * Returns the byte length of the multibyte whitespace character at the
     * given position, or zero when there is none.
     *
     * @param int<0, max> $position
     * @return int<0, 3>
     */
    private function measureWhitespace(int $position): int
    {
        foreach (self::SEQUENCES_WHITESPACE as $sequence) {
            if (\substr_compare($this->buffer, $sequence, $position, \strlen($sequence)) === 0) {
                return \strlen($sequence);
            }
        }

        return 0;
    }

    /**
     * Consumes and returns everything that is left.
     */
    public function readRemainder(): string
    {
        $rest = \substr($this->buffer, $this->current);

        $this->moveTo($this->length);

        return $rest;
    }

    /**
     * @return non-empty-string
     */
    private static function createIdentifierChars(): string
    {
        $mask = 'abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789_';

        // Every byte above 0x7F is part of a multibyte (e.g. Unicode) name,
        // except the lead bytes of multibyte whitespace.
        for ($byte = 0x80; $byte <= 0xFF; ++$byte) {
            if (self::isWhitespaceLead(\chr($byte))) {
                continue;
            }

            $mask .= \chr($byte);
        }

        return $mask;
    }
}
