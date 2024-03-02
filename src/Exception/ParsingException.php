<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\Source;

class ParsingException extends \RuntimeException implements RuntimeExceptionInterface
{
    final public const ERROR_CODE_INTERNAL = 0x01;

    protected const CODE_LAST = self::ERROR_CODE_INTERNAL;

    public readonly ReadableInterface $source;

    /**
     * @param int<0, max> $offset
     */
    final public function __construct(
        ReadableInterface|string $source,
        public readonly int $offset = 0,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        if (\is_string($source)) {
            $source = new Source($source);
        }

        $this->source = $source;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param int<0, max> $offset
     */
    public static function fromInternalError(ReadableInterface|string $source, int $offset, \Throwable $e): self
    {
        return new static(
            source: $source,
            offset: $offset,
            message: $e->getMessage(),
            code: self::ERROR_CODE_INTERNAL,
            previous: $e,
        );
    }

    /**
     * @param int<0, max> $offset
     */
    public function withSource(ReadableInterface|string $source, int $offset): self
    {
        return new static(
            source: $source,
            offset: $offset,
            message: $this->message,
            code: $this->code,
            previous: $this->getPrevious(),
        );
    }

    public function getSource(): ReadableInterface
    {
        return $this->source;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
