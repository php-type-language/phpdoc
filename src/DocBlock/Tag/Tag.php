<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

abstract class Tag implements TagInterface
{
    protected readonly ?Description $description;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
        \Stringable|string|null $description = null,
    ) {
        $this->description = match (true) {
            $description instanceof Description => $description,
            $description instanceof \Stringable => Description::create((string)$description),
            \is_string($description) => Description::create($description),
            default => $description,
        };
    }

    /**
     * @psalm-immutable
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-immutable
     */
    public function getDescription(): Description|null
    {
        return $this->description;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description?: array{
     *         template: string,
     *         tags: list<array>
     *     }
     * }
     */
    public function toArray(): array
    {
        $result = ['name' => $this->name];

        $description = $this->description;

        if (!$description instanceof Description && $description !== null) {
            $description = Description::create((string)$description);
        }

        if ($description !== null) {
            $result['description'] = $description->toArray();
        }

        return $result;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description?: array{
     *         template: string,
     *         tags: list<array>
     *     }
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        return \rtrim(\vsprintf('@%s %s', [
            $this->name,
            (string)$this->description,
        ]));
    }
}
