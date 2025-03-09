<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template T of non-empty-string
 * @template-extends Reader<T>
 */
final class ValueReader extends Reader
{
    /**
     * @var OptionalValueReader<T>
     */
    private readonly OptionalValueReader $identifier;

    /**
     * @param non-empty-string $tag
     * @param T $value
     */
    public function __construct(
        private readonly string $tag,
        private readonly string $value,
    ) {
        $this->identifier = new OptionalValueReader($value);
    }

    /**
     * @return T
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): string
    {
        /** @var T */
        return ($this->identifier)($stream)
            ?? throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect identifier value "%s"',
                $this->tag,
                $this->value,
            ));
    }
}
