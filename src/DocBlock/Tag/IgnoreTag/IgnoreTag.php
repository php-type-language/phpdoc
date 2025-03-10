<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\IgnoreTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to tell documentation systems that _Symbol_ are not to be processed.
 *
 * ```
 * "@ignore" [<description>]
 * ```
 */
final class IgnoreTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
