<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class InvalidTagPrefixException extends InvalidTagNameException
{
    /**
     * Occurs when a tag name does not start with the "@" character.
     *
     * @param int<0, max> $offset
     */
    public static function becauseTagPrefixIsInvalid(string $source, int $offset = 0): self
    {
        $message = 'The tag name must starts with the "@" character';

        return new static($source, $offset, $message);
    }
}
