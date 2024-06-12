<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Tag\Content;

/**
 * @template-extends Applicator<non-empty-string>
 */
final class VariableNameApplicator extends Applicator
{
    private readonly OptionalVariableNameApplicator $var;

    /**
     * @param non-empty-string $tag
     */
    public function __construct(
        private readonly string $tag,
    ) {
        $this->var = new OptionalVariableNameApplicator();
    }

    /**
     * @return non-empty-string
     * @throws InvalidTagException
     */
    public function __invoke(Content $lexer): string
    {
        return ($this->var)($lexer)
            ?? throw $lexer->getTagException(\sprintf(
                'Tag @%s contains an incorrect variable name',
                $this->tag,
            ));
    }
}
