<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

/**
 * @psalm-consistent-constructor
 */
class ParseException extends \InvalidArgumentException implements DocBlockExceptionInterface
{
    protected const CODE_LAST = 0x00;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
