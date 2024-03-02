<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Platform;

use TypeLang\Parser\Node\SerializableInterface;

final class UserPlatform implements PlatformInterface
{
    /**
     * @param non-empty-string $name
     * @param \Stringable|string|null $description
     */
    public function __construct(
        public readonly string $name,
        public readonly \Stringable|string|null $description = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): \Stringable|string|null
    {
        return $this->description;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null
     * }
     *
     * @psalm-immutable
     */
    public function toArray(): array
    {
        $description = $this->description;

        return [
            'name' => $this->name,
            'description' => match (true) {
                $description === null => null,
                $description instanceof SerializableInterface => $description->toArray(),
                default => (string) $description,
            },
        ];
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: array|string|null
     * }
     *
     * @psalm-immutable
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
