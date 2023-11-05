<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Exception;

/**
 * @psalm-consistent-constructor
 */
class TagWithoutNameException extends ParseException implements DocBlockExceptionInterface
{
    final public const CODE_NON_TAGGED = 0x01 + parent::CODE_LAST;

    final public const CODE_EMPTY = 0x02 + parent::CODE_LAST;

    final public const CODE_NO_NAME = 0x03 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_NO_NAME;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromNonTaggedBody(): self
    {
        $message = 'Could not extract tag name from value that does not look like tag';

        return new self($message, self::CODE_NON_TAGGED);
    }

    public static function fromEmptyBody(): self
    {
        $message = 'Could not extract tag name from empty tag';

        return new self($message, self::CODE_EMPTY);
    }

    public static function fromTagWithoutName(): self
    {
        $message = 'Could not extract tag name from tag without name';

        return new self($message, self::CODE_NO_NAME);
    }
}
