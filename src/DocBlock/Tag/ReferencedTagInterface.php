<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Reference\ReferenceInterface;

/**
 * A tag that points to another element or an external resource.
 *
 * @template-covariant TReference of ReferenceInterface = ReferenceInterface
 */
interface ReferencedTagInterface extends TagInterface
{
    /**
     * The target the tag points to.
     *
     * @var TReference
     */
    public ReferenceInterface $reference {
        get;
    }
}
