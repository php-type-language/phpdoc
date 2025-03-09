<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

/**
 * Representation of invalid tag definition.
 */
interface InvalidTagInterface extends TagInterface
{
    /**
     * Gets the reason why this tag is invalid.
     *
     * @readonly
     */
    public \Throwable $reason { get; }
}

