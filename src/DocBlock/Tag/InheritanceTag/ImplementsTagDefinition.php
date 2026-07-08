<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The `@implements` tag makes a generic interface concrete by providing the
 * type arguments for its template parameters.
 *
 * ```
 * "@implements" <Type> [ <Description> ]
 * "@template-implements" <Type> [ <Description> ]
 * ```
 */
final class ImplementsTagDefinition extends InheritanceTagDefinition
{
    public const string NAME = 'implements';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        ?DescriptionInterface $description,
    ): ImplementsTag {
        return new ImplementsTag($name, $type, $description);
    }
}
