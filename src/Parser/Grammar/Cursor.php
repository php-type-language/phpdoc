<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar;

/**
 * A reading position over a tag suffix that grammar rules consume from.
 *
 * The reading methods cover the shapes a rule usually needs (a word, an
 * identifier, a literal, a run of characters), so a rule rarely has to touch
 * {@see $position} by hand that is reserved for rewinding a speculative match.
 */
final class Cursor
{
    /**
     * The whitespace bytes that separate words.
     */
    private const string CHARS_WHITESPACE = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}";

    /**
     * @var int<0, max>
     */
    private readonly int $length;

    /**
     * The furthest position ever reached, reported by {@see $furthestOffset}
     * when a match fails.
     *
     * @var int<0, max>
     */
    private int $furthest = 0;

    /**
     * The current position within the buffer.
     *
     * Reading advances it; assigning rewinds it to roll a speculative match
     * back. Whatever it is set to, the furthest position reached is remembered
     * for failure reporting.
     *
     * @var int<0, max>
     */
    public int $position = 0 {
        set(int $position) {
            $this->position = $position;

            if ($position > $this->furthest) {
                $this->furthest = $position;
            }
        }
    }

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
     * The current byte offset within the source.
     *
     * @var int<0, max>
     */
    public int $offset {
        get => $this->base + $this->position;
    }

    /**
     * The byte offset to report when a match fails.
     *
     * @var int<0, max>
     */
    public int $furthestOffset {
        get => $this->base + $this->furthest;
    }

    public bool $isEof {
        get => $this->position >= $this->length;
    }

    /**
     * Returns the upcoming bytes without consuming them.
     */
    public function peek(int $length = 1): string
    {
        return \substr($this->buffer, $this->position, \max(0, $length));
    }

    /**
     * Consumes and returns up to $length bytes.
     */
    public function read(int $length): string
    {
        $value = \substr($this->buffer, $this->position, \max(0, $length));
        $this->position += \strlen($value);

        return $value;
    }

    /**
     * Consumes and returns the leading run of $characters, or an empty string
     * when the current byte is not one of them.
     */
    public function readWhile(string $characters): string
    {
        return $this->read(\strspn($this->buffer, $characters, $this->position));
    }

    /**
     * Consumes and returns everything up to (but not including) the first of
     * $characters, or an empty string when the current byte is already one of
     * them.
     */
    public function readUntil(string $characters): string
    {
        return $this->read(\strcspn($this->buffer, $characters, $this->position));
    }

    /**
     * Consumes and returns the next whitespace-delimited word, or an empty
     * string when none is left.
     */
    public function readWord(): string
    {
        return $this->readUntil(self::CHARS_WHITESPACE);
    }

    /**
     * Consumes and returns a name (letters, digits, "_" and any multibyte
     * character), or an empty string when the current byte starts none.
     */
    public function readPhpIdentifier(): string
    {
        return $this->readWhile(self::$phpIdentifierChars);
    }

    /**
     * Consumes and returns a namespaced name (a {@see readPhpIdentifier()} that
     * may also contain "\"), or an empty string when the current byte starts none.
     */
    public function readPhpQualifiedName(): string
    {
        return $this->readWhile('\\' . self::$phpIdentifierChars);
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

        if ($this->position + $length > $this->length
            || \substr_compare($this->buffer, $literal, $this->position, $length) !== 0
        ) {
            return false;
        }

        $this->position += $length;

        return true;
    }

    /**
     * Advances over any leading whitespace.
     */
    public function skipWhitespace(): void
    {
        $this->position += \strspn($this->buffer, self::CHARS_WHITESPACE, $this->position);
    }

    /**
     * Consumes and returns everything that is left.
     */
    public function readRemainder(): string
    {
        $rest = \substr($this->buffer, $this->position);
        $this->position = $this->length;

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

        // Every byte above 0x7F is part of a multibyte (e.g. Unicode) name.
        for ($byte = 0x80; $byte <= 0xFF; ++$byte) {
            $mask .= \chr($byte);
        }

        return $mask;
    }
}
