<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

/**
 * @psalm-consistent-constructor
 */
class InvalidTagNameException extends InvalidTagException implements DocBlockExceptionInterface
{
    final public const CODE_EMPTY = 0x01 + parent::CODE_LAST;

    final public const CODE_EMPTY_NAME = 0x02 + parent::CODE_LAST;

    final public const CODE_INVALID_PREFIX = 0x03 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_INVALID_PREFIX;

    /**
     * Occurs when a tag name is empty.
     */
    public static function fromEmptyTag(): self
    {
        $message = 'Can not read tag name from empty value';

        return new self($message, self::CODE_EMPTY);
    }

    /**
     * Occurs when a tag name contains only the "@" character.
     */
    public static function fromEmptyTagName(): self
    {
        $message = 'Tag name cannot be empty';

        return new self($message, self::CODE_EMPTY_NAME);
    }

    /**
     * Occurs when a tag name does not start with the "@" character.
     */
    public static function fromInvalidTagPrefix(): self
    {
        $message = 'The tag name must starts with the "@" character';

        return new self($message, self::CODE_INVALID_PREFIX);
    }
}
