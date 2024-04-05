<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc\Tag
 */
interface TagsProviderInterface
{
    /**
     * Returns the tags for this object.
     *
     * @psalm-immutable Each call to the method must return the same value.
     *
     * @return iterable<array-key, TagInterface>
     */
    public function getTags(): iterable;
}
