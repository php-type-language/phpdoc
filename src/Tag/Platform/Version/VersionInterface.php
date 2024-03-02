<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Platform\Version\Stability\StabilityInterface;

interface VersionInterface extends SerializableInterface, \Stringable
{
    /**
     * @param VersionInterface|non-empty-string $version
     *
     * @return int<-1, 1>
     */
    public function compare(VersionInterface|string $version): int;

    /**
     * @return int<0, max>
     */
    public function getMajor(): int;

    /**
     * @return int<0, max>
     */
    public function getMinor(): int;

    /**
     * @return int<0, max>
     */
    public function getPatch(): int;

    /**
     * @return int<0, max>
     */
    public function getRevision(): int;

    /**
     * Returns version stability.
     */
    public function getStability(): StabilityInterface;

    /**
     * @return non-empty-string
     */
    public function __toString(): string;
}
