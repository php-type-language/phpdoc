<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

use Phplrt\Contracts\Source\ReadableInterface;

/**
 * Error occurring while processing phpdoc content.
 */
interface RuntimeExceptionInterface extends PHPDocExceptionInterface
{
    /**
     * Returns a new exception with the given source and offset.
     *
     * @param int<0, max> $offset
     */
    public function withSource(ReadableInterface|string $source, int $offset): self;

    /**
     * Returns the full content in which the error occurred.
     */
    public function getSource(): ReadableInterface;

    /**
     * Returns the byte offset at the location where the error occurs.
     *
     * @return int<0, max>
     */
    public function getOffset(): int;
}
