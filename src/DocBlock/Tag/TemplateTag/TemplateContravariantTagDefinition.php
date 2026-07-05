<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@template-contravariant`" tag declares a contravariant generic type
 * parameter.
 *
 * ```
 * "@template-contravariant" <Name> [ "of" <Type> ] [ "=" <Type> ] [ <Description> ]
 * ```
 */
final class TemplateContravariantTagDefinition extends TypeParameterTagDefinition
{
    public const string NAME = 'template-contravariant';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        string $parameter,
        ?TypeReference $bound,
        ?TypeReference $default,
        ?DescriptionInterface $description,
    ): TemplateContravariantTag {
        return new TemplateContravariantTag($name, $parameter, $bound, $default, $description);
    }
}
