<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Tag\Content;

/**
 * @template-extends Applicator<non-empty-string|null>
 */
final class OptionalIdentifierApplicator extends Applicator
{
    /**
     * @return non-empty-string|null
     */
    public function __invoke(Content $lexer): ?string
    {
        // @phpstan-ignore-next-line : PHPStan false positive
        if ($lexer->value === '') {
            return null;
        }

        \preg_match('/([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\b/u', $lexer->value, $matches);

        if (\count($matches) !== 2) {
            return null;
        }

        $lexer->shift(\strlen($matches[0]));

        return $matches[1];
    }
}
