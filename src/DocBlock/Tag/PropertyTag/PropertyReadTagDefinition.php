<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PropertyTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * The "`@property-read`" tag declares a magic property that can only be read.
 *
 * ```
 * "@property-read" <Type> <Variable> [ <Description> ]
 * ```
 */
final class PropertyReadTagDefinition extends MagicPropertyTagDefinition
{
    public const string NAME = 'property-read';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    protected function make(
        string $name,
        TypeReference $type,
        string $variable,
        ?DescriptionInterface $description,
    ): PropertyReadTag {
        return new PropertyReadTag($name, $type, $variable, $description);
    }
}
