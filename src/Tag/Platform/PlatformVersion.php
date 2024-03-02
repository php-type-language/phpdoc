<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

use TypeLang\PHPDoc\Tag\Platform\Version\Version;
use TypeLang\PHPDoc\Tag\Platform\Version\VersionInterface;

final class PlatformVersion implements PlatformVersionInterface
{
    public readonly VersionInterface $since;

    public readonly VersionInterface|null $until;

    public function __construct(
        public readonly PlatformInterface $platform,
        VersionInterface|\Stringable|string $since = new Version(),
        VersionInterface|\Stringable|string|null $until = null,
    ) {
        if (!$since instanceof VersionInterface) {
            $since = Version::parse($since);
        }

        $until = match (true) {
            $until === null => null,
            $until instanceof VersionInterface => $until,
            default => Version::parse($until),
        };

        [$this->since, $this->until] = $until?->compare($since) === -1
            ? [$until, $since]
            : [$since, $until];
    }

    public static function create(PlatformInterface $platform): self
    {
        return new self($platform);
    }

    /**
     * @param VersionInterface|\Stringable|non-empty-string $since
     */
    public function since(VersionInterface|\Stringable|string $since): self
    {
        return new self($this->platform, $since, $this->until);
    }

    /**
     * @param VersionInterface|\Stringable|non-empty-string|null $until
     */
    public function until(VersionInterface|\Stringable|string|null $until): self
    {
        return new self($this->platform, $this->since, $until);
    }

    public function getSupportedVersionSince(): VersionInterface
    {
        return $this->since;
    }

    public function getSupportedVersionUntil(): ?VersionInterface
    {
        return $this->until;
    }

    public function getPlatform(): PlatformInterface
    {
        return $this->platform;
    }

    /**
     * @return array{
     *     since: non-empty-string,
     *     until: non-empty-string|null,
     *     ...
     * }
     */
    public function toArray(): array
    {
        $since = $this->since;
        $until = $this->until;

        return [
            ...$this->platform->toArray(),
            'since' => (string) $since,
            'until' => $until === null ? null : (string) $until,
        ];
    }

    /**
     * @return array{
     *     since: non-empty-string,
     *     until: non-empty-string|null,
     *     ...
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
