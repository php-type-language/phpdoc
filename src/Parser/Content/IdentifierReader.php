<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-implements ReaderInterface<non-empty-string>
 */
final class IdentifierReader implements ReaderInterface
{
    private readonly OptionalIdentifierReader $id;

    public function __construct()
    {
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
                $stream->tag,
            ));
    }
}
