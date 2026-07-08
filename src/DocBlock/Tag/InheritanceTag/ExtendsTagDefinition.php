<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The `@extends` tag makes a generic parent class or interface concrete by
 * providing the type arguments for its template parameters.
 *
 * ```
 * "@extends" <Type> [ <Description> ]
 * "@inherits" <Type> [ <Description> ]
 * "@template-extends" <Type> [ <Description> ]
 * ```
 */
final class ExtendsTagDefinition extends InheritanceTagDefinition
{
    public const string NAME = 'extends';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        ?DescriptionInterface $description,
    ): ExtendsTag {
        return new ExtendsTag($name, $type, $description);
    }
}
