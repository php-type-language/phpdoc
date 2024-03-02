<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

/**
 * @mixin PlatformVersionProviderInterface
 * @psalm-require-implements PlatformVersionProviderInterface
 */
trait PlatformVersionProvider
{
    /**
     * @var list<PlatformVersionInterface>
     */
    private array $versions = [];

    /**
     * @param iterable<array-key, PlatformInterface|PlatformVersionInterface> $versions
     */
    protected function loadPlatformVersions(iterable $versions = []): void
    {
        foreach ($versions as $version) {
            if ($version instanceof PlatformInterface) {
                $version = new PlatformVersion($version);
            }

            $this->versions[] = $version;
        }
    }

    protected function loadAnyLanguageAwarePlatformVersions(): void
    {
        $this->loadPlatformVersions(Platform::ANY_LANGUAGE_AWARE);
    }

    protected function loadAnyStaticAnalysisPlatformVersions(): void
    {
        $this->loadPlatformVersions(Platform::ANY_STATIC_ANALYSIS);
    }

    /**
     * {@inheritDoc}
     *
     * @return list<PlatformVersionInterface>
     */
    public function getSupportedPlatformVersions(): array
    {
        return $this->versions;
    }

    public function isSupportedBy(string|PlatformInterface|PlatformVersionInterface $platform): bool
    {
        return match (true) {
            $platform instanceof PlatformVersionInterface => $this->isSupportedByPlatformVersion($platform),
            $platform instanceof PlatformInterface => $this->isSupportedByPlatformString($platform->getName()),
            default => $this->isSupportedByPlatformString($platform),
        };
    }

    protected function isSupportedByPlatformVersion(PlatformVersionInterface $version): bool
    {
        $expectedPlatform = $version->getPlatform();

        $actualVersion = $this->findSupportedPlatformVersionByName($expectedPlatform->getName());

        if ($actualVersion === null) {
            return false;
        }

        $actualVersionSince = $actualVersion->getSupportedVersionSince();

        // Return false in minimal supported version less than expected.
        if ($actualVersionSince->compare($version->getSupportedVersionSince()) > 0) {
            return false;
        }

        $expectedVersionUntil = $version->getSupportedVersionUntil()
            ?? $version->getSupportedVersionSince();

        return ($actualVersionUntil = $actualVersion->getSupportedVersionUntil()) === null
            || $actualVersionUntil->compare($expectedVersionUntil) >= 0;
    }

    protected function isSupportedByPlatformString(string $platform): bool
    {
        return $this->findSupportedPlatformVersionByName($platform) !== null;
    }

    protected function findSupportedPlatformVersionByName(string $platform): ?PlatformVersionInterface
    {
        if ($platform === '') {
            return null;
        }

        foreach ($this->versions as $version) {
            $actual = $version->getPlatform();

            if ($actual->getName() === $platform) {
                return $version;
            }
        }

        return null;
    }
}
