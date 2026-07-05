<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@template`" tag declares an invariant generic type parameter.
 *
 * ```
 * "@template" <Name> [ "of" <Type> ] [ "=" <Type> ] [ <Description> ]
 * ```
 */
final class TemplateTagDefinition extends TypeParameterTagDefinition
{
    public const string NAME = 'template';

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
    ): TemplateTag {
        return new TemplateTag($name, $parameter, $bound, $default, $description);
    }
}
