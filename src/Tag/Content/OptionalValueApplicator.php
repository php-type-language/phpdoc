<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Tag\Content;

/**
 * @template T of non-empty-string
 * @template-extends Applicator<T|null>
 */
final class OptionalValueApplicator extends Applicator
{
    /**
     * @param T $value
     */
    public function __construct(
        private readonly string $value,
    ) {}

    /**
     * @return T|null
     */
    public function __invoke(Content $lexer): ?string
    {
        if (!\str_starts_with($lexer->value, $this->value)) {
            return null;
        }

        $expectedLength = \strlen($this->value);

        // In case of end of string
        if ($expectedLength === \strlen(\rtrim($lexer->value))) {
            return $this->apply($lexer);
        }

        // In case of separated by whitespace
        if (\ctype_space($lexer->value[$expectedLength])) {
            return $this->apply($lexer);
        }

        return null;
    }

    /**
     * @return T
     */
    private function apply(Content $lexer): string
    {
        $lexer->shift(\strlen($this->value));

        return $this->value;
    }
}
