<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

final class TagParsingException extends ParsingException
{
    /**
     * @param int<0, max> $offset
     */
    public static function becauseInternalErrorOccurs(
        \Throwable $previous,
        string $source,
        int $offset = 0,
    ): self {
        return new self(
            source: $source,
            offset: $offset,
            message: $previous->getMessage(),
            previous: $previous,
        );
    }
}
