<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The `@psalm-check-type` tag reports an issue unless the inferred type of
 * the given variable is assignable to the expected type.
 *
 * ```
 * "@psalm-check-type" <Variable> "=" <Type>
 * ```
 */
final class PsalmCheckTypeTagDefinition extends CheckTypeTagDefinition
{
    public const string NAME = 'psalm-check-type';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        string $variable,
        TypeReference $type,
    ): PsalmCheckTypeTag {
        return new PsalmCheckTypeTag($name, $variable, $type);
    }
}
