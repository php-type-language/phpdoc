<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

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
    public function __construct(
        /**
         * @var array<non-empty-lowercase-string, TagDefinitionInterface>
         */
        private array $definitions,
        private TagDefinitionInterface $genericTagDefinition,
    ) {}

    public function has(string $name): bool
    {
        return isset($this->definitions[\strtolower($name)]);
    }

    public function get(string $name): TagDefinitionInterface
    {
        return $this->definitions[\strtolower($name)]
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
