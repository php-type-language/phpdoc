<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Exception;

abstract class ParsingException extends \RuntimeException implements ParsingExceptionInterface
{
    final public function __construct(
        public readonly string $source,
        /**
         * @var int<0, max>
         */
        public readonly int $offset = 0,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns a copy of this exception rebased onto the full docblock.
     *
     * @param int<0, max> $offset byte offset of the fragment inside $source
     */
    final public function withSource(string $source, int $offset = 0): static
    {
        return new static(
            source: $source,
            offset: $offset,
            message: $this->getMessage(),
            code: $this->getCode(),
            previous: $this->getPrevious(),
        );
    }
}
