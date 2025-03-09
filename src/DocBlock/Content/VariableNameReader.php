<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;

/**
 * @template-extends Reader<non-empty-string>
 */
final class VariableNameReader extends Reader
{
    private readonly OptionalVariableNameReader $var;

    /**
     * @param non-empty-string $tag
     */
    public function __construct(
        private readonly string $tag,
    ) {
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
                $this->tag,
            ));
    }
}
