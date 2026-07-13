<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\Tag\GenericTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Exception\RecursiveTagAliasException;
use TypeLang\PhpDoc\Exception\UnknownTagAliasException;
use TypeLang\PhpDoc\TagRegistryInterface;

final class TagRegistryBuilder
{
    /**
     * @var array<non-empty-lowercase-string, TagDefinitionInterface>
     */
    private array $definitions = [];

    /**
     * @var array<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    private array $aliases = [];

    /**
     * @param iterable<non-empty-string, TagDefinitionInterface> $definitions
     * @param iterable<non-empty-string, non-empty-string> $aliases
     */
    public function __construct(
        iterable $definitions = [],
        iterable $aliases = [],
        private TagDefinitionInterface $genericTagDefinition = new GenericTagDefinition(),
    ) {
        $this->addTagDefinitions($definitions);
        $this->addTagAliases($aliases);
    }

    private function setGenericTagDefinition(TagDefinitionInterface $genericTagDefinition): void
    {
        $this->genericTagDefinition = $genericTagDefinition;
    }

    public function withGenericTagDefinition(TagDefinitionInterface $definition): self
    {
        $self = clone $this;
        $self->setGenericTagDefinition($definition);

        return $self;
    }

    /**
     * @param iterable<non-empty-string, TagDefinitionInterface> $definitions
     */
    private function addTagDefinitions(iterable $definitions): void
    {
        foreach ($definitions as $name => $definition) {
            $this->definitions[\strtolower($name)] = $definition;
        }
    }

    /**
     * @param iterable<non-empty-string, TagDefinitionInterface> $definitions
     */
    public function withAddedTagDefinitions(iterable $definitions): self
    {
        $self = clone $this;
        $self->addTagDefinitions($definitions);

        return $self;
    }

    /**
     * @param iterable<non-empty-string, non-empty-string> $aliases
     */
    private function addTagAliases(iterable $aliases): void
    {
        foreach ($aliases as $alias => $name) {
            $this->aliases[\strtolower($alias)] = \strtolower($name);
        }
    }

    /**
     * @param iterable<non-empty-string, non-empty-string> $aliases
     */
    public function withAddedTagAliases(iterable $aliases): self
    {
        $self = clone $this;
        $self->addTagAliases($aliases);

        return $self;
    }

    /**
     * @return array<non-empty-lowercase-string, TagDefinitionInterface>
     * @throws UnknownTagAliasException if the alias cannot be resolved to a definition
     * @throws RecursiveTagAliasException if the alias references form a cycle
     */
    private function getAllTagDefinitions(): array
    {
        $result = $this->definitions;

        foreach ($this->aliases as $alias => $_) {
            $result[$alias] = $this->definitions[$this->getCanonicalName($alias)];
        }

        \ksort($result);

        return $result;
    }

    /**
     * Follows the alias chain until a registered tag definition is reached
     * and returns its canonical (definition) name.
     *
     * @param non-empty-lowercase-string $alias
     * @return non-empty-lowercase-string
     * @throws UnknownTagAliasException if a link in the chain has neither a definition nor an alias
     * @throws RecursiveTagAliasException if the chain references itself
     */
    private function getCanonicalName(string $alias): string
    {
        $current = $alias;

        /** @var list<non-empty-lowercase-string> $visited */
        $visited = [];

        while (!isset($this->definitions[$current])) {
            $canonical = $this->aliases[$current] ?? null;

            if ($canonical === null) {
                throw UnknownTagAliasException::becauseAliasHasNoDefinition($alias, $visited);
            }

            if (\in_array($canonical, $visited, true)) {
                throw RecursiveTagAliasException::becauseAliasIsRecursive($alias, $visited);
            }

            $visited[] = $current = $canonical;
        }

        return $current;
    }

    public function build(): TagRegistryInterface
    {
        return new TagRegistry(
            definitions: $this->getAllTagDefinitions(),
            genericTagDefinition: $this->genericTagDefinition,
        );
    }
}
