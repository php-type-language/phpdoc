<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

interface PlatformVersionProviderInterface
{
    /**
     * Returns list of supported platforms and its versions by the tag.
     *
     * @return iterable<array-key, PlatformVersionInterface>
     *
     * @psalm-immutable
     */
    public function getSupportedPlatformVersions(): iterable;

    /**
     * Returns {@see true} in case of the definition is supported by
     * the specified platform, or {@see false} instead.
     *
     * @param non-empty-string|PlatformInterface|PlatformVersionInterface $platform
     *
     * @psalm-immutable
     */
    public function isSupportedBy(string|PlatformInterface|PlatformVersionInterface $platform): bool;
}
