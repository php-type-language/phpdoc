<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The `@require-implements` tag constrains a trait so that it may only be
 * used within a class that implements the given interface.
 *
 * ```
 * "@require-implements" <Type> [ <Description> ]
 * ```
 */
final class RequireImplementsTagDefinition extends RequireInheritanceTagDefinition
{
    public const string NAME = 'require-implements';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        ?DescriptionInterface $description,
    ): RequireImplementsTag {
        return new RequireImplementsTag($name, $type, $description);
    }
}
