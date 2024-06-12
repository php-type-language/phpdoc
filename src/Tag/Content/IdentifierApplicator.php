<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Tag\Content;

/**
 * @template-extends Applicator<non-empty-string>
 */
final class IdentifierApplicator extends Applicator
{
    private readonly OptionalIdentifierApplicator $id;

    /**
     * @param non-empty-string $tag
     */
    public function __construct(
        private readonly string $tag,
    ) {
        $this->id = new OptionalIdentifierApplicator();
    }

    /**
     * @return non-empty-string
     * @throws InvalidTagException
     */
    public function __invoke(Content $lexer): string
    {
        return ($this->id)($lexer)
            ?? throw $lexer->getTagException(\sprintf(
                'Tag @%s contains an incorrect identifier value',
                $this->tag,
            ));
    }
}
