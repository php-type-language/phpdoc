<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Tag\Content;

/**
 * @template T of non-empty-string
 * @template-extends Applicator<T>
 */
final class ValueApplicator extends Applicator
{
    private readonly OptionalValueApplicator $identifier;

    /**
     * @param non-empty-string $tag
     * @param T $value
     */
    public function __construct(
        private readonly string $tag,
        private readonly string $value,
    ) {
        $this->identifier = new OptionalValueApplicator($value);
    }

    /**
     * @return T
     *
     * @throws InvalidTagException
     */
    public function __invoke(Content $lexer): string
    {
        /** @var T */
        return ($this->identifier)($lexer)
            ?? throw $lexer->getTagException(\sprintf(
                'Tag @%s contains an incorrect identifier value "%s"',
                $this->tag,
                $this->value,
            ));
    }
}
