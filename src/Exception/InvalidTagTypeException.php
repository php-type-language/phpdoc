<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

/**
 * @psalm-consistent-constructor
 */
class InvalidTagTypeException extends InvalidTagException implements DocBlockExceptionInterface
{
    final public const CODE_WITHOUT_NAME = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_WITHOUT_NAME;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromNonTyped(\Throwable $e = null): self
    {
        $message = 'Could not parse tag type';

        return new static($message, self::CODE_WITHOUT_NAME, $e);
    }
}
