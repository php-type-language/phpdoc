<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class EmptyTagNameException extends InvalidTagNameException
{
    /**
     * Occurs when a tag name is empty.
     *
     * @param int<0, max> $offset
     */
    public static function becauseTagNameIsEmpty(string $source, int $offset = 0): self
    {
        $message = \sprintf('Can not read tag name from "%s" tag line', $source);

        return new self($source, $offset, $message);
    }
}
