<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Definition;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Platform\PlatformVersionProvider;

abstract class Definition implements DefinitionInterface
{
    use PlatformVersionProvider;

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null,
     *     platforms: list<array>
     * }
     */
    public function toArray(): array
    {
        $versions = [];

        foreach ($this->versions as $version) {
            $versions[] = $version->toArray();
        }

        $description = $this->getDescription();

        return [
            'name' => $this->getName(),
            'description' => match (true) {
                $description === null => null,
                $description instanceof SerializableInterface => $description->toArray(),
                default => (string) $description,
            },
            'platforms' => $versions,
        ];
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null,
     *     platforms: list<array>
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
