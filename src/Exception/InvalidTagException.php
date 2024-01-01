<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Exception;

/**
 * @psalm-consistent-constructor
 */
class InvalidTagException extends ParseException implements DocBlockExceptionInterface
{
    final public const CODE_FROM_EXCEPTION = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_FROM_EXCEPTION;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromException(\Throwable $e): self
    {
        return new static($e->getMessage(), (int)$e->getCode(), $e);
    }
}
