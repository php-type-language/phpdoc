<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;

interface PlatformInterface
{
    /**
     * Platform name to display anywhere.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Returns a list of registered tags for the specified platform.
     *
     * @return iterable<non-empty-lowercase-string, TagFactoryInterface>
     */
    public function getTags(): iterable;
}
