<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

/**
 * @template-extends \IteratorAggregate<array-key, TagInterface>
 */
interface TagProviderInterface extends \IteratorAggregate, \Countable
{
    /**
     * Returns the tags for this DocBlock.
     *
     * @return list<TagInterface>
     */
    public function getTags(): iterable;

    /**
     * @return int<0, max>
     */
    public function count(): int;
}
