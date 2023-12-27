<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

/**
 * @psalm-consistent-constructor
 */
class InvalidTypeException extends InvalidTagException implements DocBlockExceptionInterface
{
    final public const CODE_WITHOUT_TYPE = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_WITHOUT_TYPE;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromInvalidType(\Throwable $e = null): self
    {
        $message = 'Could not parse tag type';

        return new static($message, self::CODE_WITHOUT_TYPE, $e);
    }
}
