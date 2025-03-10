<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template T of non-empty-string
 * @template-implements ReaderInterface<T>
 */
final class ValueReader implements ReaderInterface
{
    /**
     * @var OptionalValueReader<T>
     */
    private readonly OptionalValueReader $identifier;

    public function __construct(
        /**
         * @var T
         */
        private readonly string $value,
    ) {
        $this->identifier = new OptionalValueReader($value);
    }

    /**
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): string
    {
        /** @var T */
        return ($this->identifier)($stream)
            ?? throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect identifier value "%s"',
                $stream->tag,
                $this->value,
            ));
    }
}
