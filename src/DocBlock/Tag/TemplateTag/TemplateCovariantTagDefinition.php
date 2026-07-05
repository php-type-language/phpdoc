<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@template-covariant`" tag declares a covariant generic type parameter.
 *
 * ```
 * "@template-covariant" <Name> [ "of" <Type> ] [ "=" <Type> ] [ <Description> ]
 * ```
 */
final class TemplateCovariantTagDefinition extends TypeParameterTagDefinition
{
    public const string NAME = 'template-covariant';

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
    ): TemplateCovariantTag {
        return new TemplateCovariantTag($name, $parameter, $bound, $default, $description);
    }
}
