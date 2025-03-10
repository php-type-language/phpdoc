<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-implements ReaderInterface<non-empty-string>
 */
final class VariableNameReader implements ReaderInterface
{
    private readonly OptionalVariableNameReader $var;

    public function __construct()
    {
        $this->var = new OptionalVariableNameReader();
    }

    /**
     * @return non-empty-string
     * @throws InvalidTagException
     */
    public function __invoke(Stream $stream): string
    {
        return ($this->var)($stream)
            ?? throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect variable name',
                $stream->tag,
            ));
    }
}
