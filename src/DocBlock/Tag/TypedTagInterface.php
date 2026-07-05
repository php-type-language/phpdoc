<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\Type\TypeNode;

/**
 * A tag that carries a single type.
 */
interface TypedTagInterface extends TagInterface
{
    /**
     * The type declared by the tag.
     */
    public TypeNode $type {
        get;
    }
}
