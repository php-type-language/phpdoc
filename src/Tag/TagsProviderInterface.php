<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @internal this is an internal library interface, please do not use it in your code
 * @psalm-internal TypeLang\PHPDoc\Tag
 */
interface TagsProviderInterface
{
    /**
     * Returns the tags for this object.
     *
     * @return iterable<array-key, TagInterface>
     */
    public function getTags(): iterable;
}
