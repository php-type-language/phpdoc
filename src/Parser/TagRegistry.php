<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\TagRegistryInterface;

/**
 * The tag definitions keyed by the lower-case tag name, built by
 * {@see TagRegistryBuilder} with the aliases already resolved.
 *
 * @template-implements \IteratorAggregate<non-empty-lowercase-string, TagDefinitionInterface>
 */
final class TagRegistry implements TagRegistryInterface, \IteratorAggregate
{
    public function __construct(
        /**
         * @var array<non-empty-lowercase-string, TagDefinitionInterface>
         */
        private readonly array $definitions = [],
    ) {}

    public function has(string $name): bool
    {
        return isset($this->definitions[\strtolower($name)]);
    }

    public function find(string $name): ?TagDefinitionInterface
    {
        return $this->definitions[\strtolower($name)] ?? null;
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
