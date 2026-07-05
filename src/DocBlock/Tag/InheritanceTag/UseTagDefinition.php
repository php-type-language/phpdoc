<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@use`" tag makes a generic trait concrete by providing the type
 * arguments for its template parameters.
 *
 * ```
 * "@use" <Type> [ <Description> ]
 * "@template-use" <Type> [ <Description> ]
 * ```
 */
final class UseTagDefinition extends InheritanceTagDefinition
{
    public const string NAME = 'use';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        ?DescriptionInterface $description,
    ): UseTag {
        return new UseTag($name, $type, $description);
    }
}
