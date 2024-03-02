<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

use TypeLang\Parser\Node\SerializableInterface;

interface PlatformInterface extends SerializableInterface
{
    /**
     * Returns tag platform/category name.
     *
     * @return non-empty-string
     *
     * @psalm-immutable
     */
    public function getName(): string;

    /**
     * Returns a short description of the platform.
     *
     * @return \Stringable|string|null
     *
     * @psalm-immutable
     */
    public function getDescription(): \Stringable|string|null;
}
