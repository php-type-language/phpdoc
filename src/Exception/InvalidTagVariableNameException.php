<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @psalm-consistent-constructor
 */
class InvalidTagVariableNameException extends InvalidTagException implements DocBlockExceptionInterface
{
    final public const CODE_WITH_TYPE = 0x01 + parent::CODE_LAST;

    final public const CODE_WITHOUT_TYPE = 0x02 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_WITHOUT_TYPE;

    /**
     * @param int<0, max> $offset
     */
    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
        private readonly ?TypeStatement $type = null,
        private readonly int $offset = 0,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getType(): ?TypeStatement
    {
        return $this->type;
    }

    /**
     * @return int<0, max>
     */
    public function getTypeOffset(): int
    {
        return $this->offset;
    }

    public static function fromNonTyped(\Throwable $e = null): self
    {
        $message = 'Could not parse tag variable name';

        return new static($message, self::CODE_WITHOUT_TYPE, $e);
    }

    /**
     * @param int<0, max> $offset
     */
    public static function fromTyped(TypeStatement $type, int $offset, \Throwable $e = null): self
    {
        $message = 'Could not parse tag variable name';

        return new static($message, self::CODE_WITH_TYPE, $e, $type, $offset);
    }
}
