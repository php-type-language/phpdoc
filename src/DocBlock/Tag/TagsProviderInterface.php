<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

/**
 * Representation of any entry that contain inner tags list.
 */
interface TagsProviderInterface
{
    /**
     * Gets tags list for this object.
     *
     * @var iterable<array-key, TagInterface>
     *
     * @readonly
     */
    public iterable $tags { get; }
}
