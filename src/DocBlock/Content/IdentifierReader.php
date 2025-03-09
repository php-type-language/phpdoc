<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-extends Reader<non-empty-string>
 */
final class IdentifierReader extends Reader
{
    private readonly OptionalIdentifierReader $id;

    /**
     * @param non-empty-string $tag
     */
    public function __construct(
        private readonly string $tag,
    ) {
        $this->id = new OptionalIdentifierReader();
    }

    /**
     * @return non-empty-string
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): string
    {
        return ($this->id)($stream)
            ?? throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect identifier value',
                $this->tag,
            ));
    }
}
