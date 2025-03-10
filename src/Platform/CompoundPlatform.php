<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Platform;

final class CompoundPlatform implements PlatformInterface
{
    /**
     * @var list<PlatformInterface>
     */
    public readonly array $platforms;

    /**
     * @param iterable<array-key, PlatformInterface> $platforms
     */
    public function __construct(iterable $platforms = [])
    {
        $this->platforms = match (true) {
            $platforms instanceof \Traversable => \iterator_to_array($platforms, false),
            \array_is_list($platforms) => $platforms,
            default => \array_values($platforms),
        };
    }

    public function getName(): string
    {
        if ($this->platforms === []) {
            return 'empty';
        }

        $names = [];

        foreach ($this->platforms as $platform) {
            $names[] = $platform->getName();
        }

        return \implode(' & ', $names);
    }

    public function getTags(): iterable
    {
        foreach ($this->platforms as $platform) {
            yield from $platform->getTags();
        }
    }
}
