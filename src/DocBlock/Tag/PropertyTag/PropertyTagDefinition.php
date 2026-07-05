<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PropertyTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@property`" tag declares a magic property that can be both read and
 * written.
 *
 * ```
 * "@property" <Type> <Variable> [ <Description> ]
 * ```
 */
final class PropertyTagDefinition extends MagicPropertyTagDefinition
{
    public const string NAME = 'property';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        string $variable,
        ?DescriptionInterface $description,
    ): PropertyTag {
        return new PropertyTag($name, $type, $variable, $description);
    }
}
