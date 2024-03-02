<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform\Version;

use Composer\Semver\VersionParser;
use TypeLang\PHPDoc\Tag\Platform\Version\Stability\Stability;
use TypeLang\PHPDoc\Tag\Platform\Version\Stability\StabilityInterface;
use TypeLang\PHPDoc\Tag\Platform\Version\Stability\Stable;

/**
 * @psalm-consistent-constructor
 */
class Version implements VersionInterface
{
    /**
     * @param int<0, max> $major
     * @param int<0, max> $minor
     * @param int<0, max> $patch
     * @param int<0, max> $revision
     */
    public function __construct(
        public readonly int $major = 1,
        public readonly int $minor = 0,
        public readonly int $patch = 0,
        public readonly int $revision = 0,
        public readonly StabilityInterface $stability = new Stable(),
    ) {}

    public function compare(VersionInterface|string $version): int
    {
        if (\is_string($version)) {
            $version = static::parse($version);
        }

        return $this->getMajor() <=> $version->getMajor()
            ?: $this->getMinor() <=> $version->getMinor()
            ?: $this->getPatch() <=> $version->getPatch()
            ?: $this->getRevision() <=> $version->getRevision()
            ?: $this->compareStability($version);
    }

    /**
     * @return int<-1, 1>
     */
    private function compareStability(VersionInterface $version): int
    {
        $stability = $this->getStability();

        return $stability->compare($version->getStability());
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public static function parse(string|\Stringable $version): self
    {
        $normalized = (new VersionParser())
            ->normalize((string) $version);

        /**
         * @psalm-suppress PossiblyUndefinedArrayOffset
         */
        [$major, $minor, $patch, $revision, $stability] = \sscanf($normalized, '%d.%d.%d.%d-%s');

        return new static(
            major: \max($major, 0),
            minor: \max($minor, 0),
            patch: \max($patch, 0),
            revision: \max($revision, 0),
            stability: Stability::parse($stability ?? ''),
        );
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public function getPatch(): int
    {
        return $this->patch;
    }

    public function getRevision(): int
    {
        return $this->revision;
    }

    public function getStability(): StabilityInterface
    {
        return $this->stability;
    }

    /**
     * @return array{
     *     major: int<0, max>,
     *     minor: int<0, max>,
     *     patch: int<0, max>,
     *     revision: int<0, max>,
     *     stability: array
     * }
     */
    public function toArray(): array
    {
        $stability = $this->getStability();

        return [
            'major' => $this->getMajor(),
            'minor' => $this->getMinor(),
            'patch' => $this->getPatch(),
            'revision' => $this->getRevision(),
            'stability' => $stability->toArray(),
        ];
    }

    /**
     * @return array{
     *     major: int<0, max>,
     *     minor: int<0, max>,
     *     patch: int<0, max>,
     *     revision: int<0, max>,
     *     stability: array
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        $version = \vsprintf('%d.%d.%d.%d', [
            $this->getMajor(),
            $this->getMinor(),
            $this->getPatch(),
            $this->getRevision(),
        ]);

        if ($this->stability instanceof Stable) {
            return $version;
        }

        return $version . '-' . $this->getStability();
    }
}
