<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Exception;

use Phplrt\Contracts\Source\ReadableInterface;

class InvalidTagNameException extends InvalidTagException
{
    final public const ERROR_CODE_EMPTY = 0x01 + parent::CODE_LAST;

    final public const ERROR_CODE_EMPTY_NAME = 0x02 + parent::CODE_LAST;

    final public const ERROR_CODE_INVALID_PREFIX = 0x03 + parent::CODE_LAST;

    protected const CODE_LAST = self::ERROR_CODE_INVALID_PREFIX;

    /**
     * Occurs when a tag name is empty.
     *
     * @param int<0, max> $offset
     */
    public static function fromEmptyTag(int $offset = 0): static
    {
        $message = 'Can not read tag name from empty value';

        return new static('', $offset, $message, self::ERROR_CODE_EMPTY);
    }

    /**
     * Occurs when a tag name contains only the "@" character.
     *
     * @param int<0, max> $offset
     */
    public static function fromEmptyTagName(ReadableInterface|string $source, int $offset = 0): static
    {
        $message = 'Tag name cannot be empty';

        return new static($source, $offset, $message, self::ERROR_CODE_EMPTY_NAME);
    }

    /**
     * Occurs when a tag name does not start with the "@" character.
     *
     * @param int<0, max> $offset
     */
    public static function fromInvalidTagPrefix(ReadableInterface|string $source, int $offset = 0): static
    {
        $message = 'The tag name must starts with the "@" character';

        return new static($source, $offset, $message, self::ERROR_CODE_INVALID_PREFIX);
    }
}
