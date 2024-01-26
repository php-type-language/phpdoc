<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Exception;

/**
 * @psalm-consistent-constructor
 * @phpstan-consistent-constructor
 */
class InvalidTypeException extends InvalidTagException implements DocBlockExceptionInterface
{
    final public const ERROR_CODE_WITHOUT_TYPE = 0x01 + parent::CODE_LAST;

    protected const CODE_LAST = self::ERROR_CODE_WITHOUT_TYPE;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromInvalidType(\Throwable $e = null): self
    {
        $message = 'Could not parse tag type';

        return new static($message, self::ERROR_CODE_WITHOUT_TYPE, $e);
    }
}
