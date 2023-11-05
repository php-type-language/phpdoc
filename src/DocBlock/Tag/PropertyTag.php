<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add support of property name parsing: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/property.html#property-property-read-property-write}
 */
final class PropertyTag extends TypedTag
{
    public function __construct(TypeStatement $type, Description|string|null $description = null)
    {
        parent::__construct('property', $type, $description);
    }
}
