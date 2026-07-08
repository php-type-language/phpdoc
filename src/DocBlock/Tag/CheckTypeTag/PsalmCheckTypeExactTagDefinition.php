<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The `@psalm-check-type-exact` tag reports an issue unless the inferred type
 * of the given variable is exactly the expected type.
 *
 * ```
 * "@psalm-check-type-exact" <Variable> "=" <Type>
 * ```
 */
final class PsalmCheckTypeExactTagDefinition extends CheckTypeTagDefinition
{
    public const string NAME = 'psalm-check-type-exact';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        string $variable,
        TypeReference $type,
    ): PsalmCheckTypeExactTag {
        return new PsalmCheckTypeExactTag($name, $variable, $type);
    }
}
