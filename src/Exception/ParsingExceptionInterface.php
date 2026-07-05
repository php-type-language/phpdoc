<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

/**
 * Error occurring while processing phpdoc content.
 */
interface ParsingExceptionInterface extends PhpDocExceptionInterface
{
    /**
     * Gets the full docblock content in which the error occurred.
     */
    public string $source {
        get;
    }

    /**
     * Gets the byte offset at the location where the error occurs.
     *
     * @var int<0, max>
     */
    public int $offset {
        get;
    }
}
