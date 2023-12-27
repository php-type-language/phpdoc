<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @psalm-consistent-constructor
 */
class InvalidVariableNameException extends InvalidTagException implements InvalidTypedTagExceptionInterface
{
    final public const CODE_WITH_TYPE = 0x01 + parent::CODE_LAST;

    final public const CODE_WITHOUT_TYPE = 0x02 + parent::CODE_LAST;

    final public const CODE_EMPTY = 0x03 + parent::CODE_LAST;

    final public const CODE_EMPTY_NAME = 0x04 + parent::CODE_LAST;

    final public const CODE_INVALID_PREFIX = 0x05 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_INVALID_PREFIX;

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
        $message = 'Could not parse tag variable name, expected "<@tag> <$name> [<description>]"';

        return new static($message, self::CODE_WITHOUT_TYPE, $e);
    }

    /**
     * @param int<0, max> $offset
     */
    public static function fromTyped(TypeStatement $type, int $offset, \Throwable $e = null): self
    {
        $message = 'Could not parse typed tag variable name, expected "<@tag> <type> <$name> [<description>]"';

        return new static($message, self::CODE_WITH_TYPE, $e, $type, $offset);
    }

    /**
     * Occurs when a variable name is empty.
     */
    public static function fromEmptyVariable(): self
    {
        $message = 'Can not read variable name from empty value';

        return new self($message, self::CODE_EMPTY);
    }

    /**
     * Occurs when a variable name contains only the "$" character.
     */
    public static function fromEmptyVariableName(): self
    {
        $message = 'Variable name cannot be empty';

        return new self($message, self::CODE_EMPTY_NAME);
    }

    /**
     * Occurs when a variable name does not start with the "$" character.
     */
    public static function fromInvalidVariablePrefix(): self
    {
        $message = 'The variable name must starts with the "$" character';

        return new self($message, self::CODE_INVALID_PREFIX);
    }
}
