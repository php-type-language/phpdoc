<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * A tag that carries an optional version.
 */
interface VersionedTagInterface extends TagInterface
{
    /**
     * The version the tag refers to, or {@see null} when none was given.
     *
     * @var non-empty-string|null
     */
    public ?string $version {
        get;
    }
}
