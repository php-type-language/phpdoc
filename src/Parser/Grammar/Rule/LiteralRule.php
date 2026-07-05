<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Grammar\Rule;

use TypeLang\PhpDoc\Parser\Grammar\Context;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Matches an exact literal at the current position.
 */
final readonly class LiteralRule implements TerminalInterface
{
    public function __construct(
        /**
         * @var non-empty-string
         */
        private string $value,
        /**
         * @var non-empty-string|null
         */
        public ?string $alias = null,
    ) {}

    public function match(Context $context): void
    {
        $cursor = $context->cursor;

        // A literal ending in a word character must be followed by a word
        // boundary, so that e.g. "of" does not match the start of "offset".
        if (self::isWordByte($this->value[\strlen($this->value) - 1])) {
            $lookahead = $cursor->peek(\strlen($this->value) + 1);

            if (\strlen($lookahead) > \strlen($this->value)
                && self::isWordByte($lookahead[\strlen($this->value)])
            ) {
                throw new NoMatchException(\sprintf('Expected "%s"', $this->value));
            }
        }

        if (!$cursor->readLiteral($this->value)) {
            throw new NoMatchException(\sprintf('Expected "%s"', $this->value));
        }

        if ($this->alias !== null) {
            $context->capture($this->alias, true);
        }
    }

    /**
     * Whether the byte may continue an identifier (letter, digit, "_" or any
     * multibyte character).
     */
    private static function isWordByte(string $byte): bool
    {
        if ($byte === '') {
            return false;
        }

        $code = \ord($byte);

        return $byte === '_'
            || $code >= 0x80
            || ($code >= 0x30 && $code <= 0x39)
            || ($code >= 0x41 && $code <= 0x5A)
            || ($code >= 0x61 && $code <= 0x7A);
    }

    public function __toString(): string
    {
        return \sprintf('"%s"', $this->value);
    }
}
