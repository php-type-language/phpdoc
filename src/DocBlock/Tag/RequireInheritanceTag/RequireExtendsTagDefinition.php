<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@require-extends`" tag constrains a trait so that it may only be used
 * within a class that extends the given type.
 *
 * ```
 * "@require-extends" <Type> [ <Description> ]
 * ```
 */
final class RequireExtendsTagDefinition extends RequireInheritanceTagDefinition
{
    public const string NAME = 'require-extends';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        ?DescriptionInterface $description,
    ): RequireExtendsTag {
        return new RequireExtendsTag($name, $type, $description);
    }
}
