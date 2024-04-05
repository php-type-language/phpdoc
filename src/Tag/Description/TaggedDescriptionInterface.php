<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

use TypeLang\PHPDoc\Tag\TagInterface;
use TypeLang\PHPDoc\Tag\TagsProviderInterface;

/**
 * Any class that implements this interface is a description object
 * containing an arbitrary set of nested tags ({@see TagInterface}) and,
 * like a parent {@see DescriptionInterface}, can be represented as a string.
 *
 * @template-implements \Traversable<int<0, max>, DescriptionInterface|TagInterface>
 */
interface TaggedDescriptionInterface extends
    TagsProviderInterface,
    DescriptionInterface,
    \Traversable,
    \Countable
{
    /**
     * Returns a list of components (that is, tags of {@see TagInterface}
     * and descriptions of {@see DescriptionInterface}) that make up the
     * {@see TaggedDescriptionInterface} in the order in which these
     * elements are defined.
     *
     * @return iterable<array-key, TagInterface|DescriptionInterface>
     */
    public function getComponents(): iterable;
}
