<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc\Tag\Platform
 *
 * @psalm-immutable
 */
#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final class Info
{
    /**
     * @param non-empty-string|null $name
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly \Stringable|string|null $description = null,
    ) {}
}
