<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Platform\Version\VersionInterface;

interface PlatformVersionInterface extends SerializableInterface
{
    /**
     * Returns information about the version since which the
     * given tag is supported.
     *
     * @psalm-immutable
     */
    public function getSupportedVersionSince(): VersionInterface;

    /**
     * Returns information about the version ending with which the given
     * tag is supported.
     *
     * @psalm-immutable
     */
    public function getSupportedVersionUntil(): ?VersionInterface;

    /**
     * Returns information about the platform on which the tag is supported.
     *
     * @psalm-immutable
     */
    public function getPlatform(): PlatformInterface;
}
