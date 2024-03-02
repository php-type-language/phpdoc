<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

final class InternalError extends \RuntimeException
{
    public function __construct(
        public readonly int $offset = 0,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return \trim($this->message) . ' at ' . $this->offset;
    }
}
