<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\Tag\GenericTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\TagRegistryInterface;

/**
 * Builds a tag from its name and suffix using the matching
 * {@see TagDefinitionInterface}.
 *
 * A tag with no registered definition becomes a plain {@see Tag} whose whole
 * suffix is its description.
 *
 * ```
 * $factory = new TagFactory(
 *     definitions: ['link' => new LinkTagDefinition()],
 *     grammar: $grammar,
 * );
 * ```
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 *
 * @template-implements \IteratorAggregate<non-empty-lowercase-string, TagDefinitionInterface>
 */
final readonly class TagRegistry implements TagRegistryInterface, \IteratorAggregate
{
    /**
     * @var array<non-empty-lowercase-string, TagDefinitionInterface>
     */
    private array $definitions;

    /**
     * @var array<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    private array $aliases;

    /**
     * @param iterable<non-empty-string, TagDefinitionInterface> $definitions
     * @param iterable<non-empty-string, non-empty-string> $aliases
     */
    public function __construct(
        iterable $definitions = [],
        iterable $aliases = [],
        private TagDefinitionInterface $genericTagDefinition = new GenericTagDefinition(),
    ) {
        $this->definitions = $this->formatDefinitions($definitions);
        $this->aliases = $this->formatAliases($aliases);
    }

    /**
     * @param iterable<non-empty-string, TagDefinitionInterface> $definitions
     * @return array<non-empty-lowercase-string, TagDefinitionInterface>
     */
    private function formatDefinitions(iterable $definitions): array
    {
        $result = [];

        foreach ($definitions as $name => $definition) {
            $result[\strtolower($name)] = $definition;
        }

        \ksort($result);

        return $result;
    }

    /**
     * @param iterable<non-empty-string, non-empty-string> $aliases
     * @return array<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    private function formatAliases(iterable $aliases): array
    {
        $result = [];

        foreach ($aliases as $alias => $name) {
            $result[\strtolower($alias)] = \strtolower($name);
        }

        return $result;
    }

    public function get(string $name): TagDefinitionInterface
    {
        // normalize
        $name = \strtolower($name);
        // canonicalize
        $name = $this->aliases[$name] ?? $name;

        return $this->definitions[$name]
            ?? $this->genericTagDefinition;
    }

    public function getIterator(): \Traversable
    {
        /** @var \ArrayIterator<non-empty-lowercase-string, TagDefinitionInterface> */
        return new \ArrayIterator($this->definitions);
    }

    public function count(): int
    {
        return \count($this->definitions);
    }
}
