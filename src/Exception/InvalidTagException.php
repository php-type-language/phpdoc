<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

class InvalidTagException extends ParsingException
{
    final public const ERROR_CODE_PARSING = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::ERROR_CODE_PARSING;

    /**
     * Occurs when a tag contain creation error.
     *
     * @param non-empty-string $tag
     * @param int<0, max> $offset
     */
    public static function fromCreatingTag(
        string $tag,
        string $source,
        int $offset = 0,
        \Throwable $prev = null,
    ): static {
        $message = \sprintf('Error while parsing tag @%s', $tag);

        return new static($source, $offset, $message, self::ERROR_CODE_PARSING, $prev);
    }
}
