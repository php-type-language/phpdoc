<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;
use TypeLang\PHPDoc\Tag\Description\Description;

/**
 * @template TDefinition of DefinitionInterface
 *
 * @template-implements TagInterface<TDefinition>
 */
abstract class Tag implements TagInterface
{
    protected readonly ?Description $description;

    /**
     * @param TDefinition $definition
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly DefinitionInterface $definition,
        protected readonly string $name,
        \Stringable|string|null $description = null,
    ) {
        $this->description = Description::fromStringOrNull($description);
    }

    /**
     * @return TDefinition
     */
    public function getDefinition(): DefinitionInterface
    {
        return $this->definition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): Description|null
    {
        return $this->description;
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: null|array{
     *         template: array|string,
     *         tags: list<array>
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description?->toArray(),
        ];
    }

    /**
     * @return array{
     *     name: non-empty-string,
     *     description: null|array{
     *         template: array|string,
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
        if ($this->description === null) {
            return \sprintf('@%s', $this->name);
        }

        return \rtrim(\vsprintf('@%s %s', [
            $this->name,
            (string) $this->description,
        ]));
    }
}
