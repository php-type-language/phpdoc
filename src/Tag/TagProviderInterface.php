<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @template-extends \Traversable<array-key, TagInterface>
 *
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc\Tag
 */
interface TagProviderInterface extends \Traversable, \Countable
{
    /**
     * Returns the tags for this DocBlock.
     *
     * @psalm-immutable
     * @return list<TagInterface>
     */
    public function getTags(): iterable;

    /**
     * @psalm-immutable
     * @return int<0, max>
     */
    public function count(): int;
}
