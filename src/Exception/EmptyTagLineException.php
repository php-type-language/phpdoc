<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class EmptyTagLineException extends InvalidTagNameException
{
    /**
     * Occurs when a tag line is empty.
     *
     * @param int<0, max> $offset
     */
    public static function becauseTagLineIsEmpty(int $offset = 0): self
    {
        return new self('', $offset, 'Can not read tag name from empty tag line');
    }
}
