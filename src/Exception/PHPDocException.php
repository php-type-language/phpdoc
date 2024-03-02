<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

class PHPDocException extends \DomainException implements PHPDocExceptionInterface
{
    protected const CODE_LAST = 0x00;

    final public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
