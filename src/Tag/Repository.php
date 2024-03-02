<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;
use TypeLang\PHPDoc\Tag\Definition\VarDefinition;

/**
 * @template-implements \IteratorAggregate<array-key, DefinitionInterface>
 */
final class Repository implements MutableRepositoryInterface, \IteratorAggregate
{
    /**
     * @var array<non-empty-string, DefinitionInterface>
     */
    private array $definitions = [];

    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private array $aliases = [];

    /**
     * @var list<non-empty-string>
     */
    private array $prefixes = [];

    public function __construct(
        private readonly ?RepositoryInterface $parent = null,
    ) {
        $this->add(new VarDefinition());
    }

    public function add(DefinitionInterface $definition): void
    {
        $this->definitions[$definition->getName()] = $definition;
    }

    public function alias(string $alias, string $tag): void
    {
        $this->aliases[$alias] = $tag;
    }

    public function prefix(string $prefix): void
    {
        $this->prefixes[] = $prefix;
    }

    /**
     * @param non-empty-string $tag
     */
    public function findByName(string $tag): ?DefinitionInterface
    {
        if (isset($this->aliases[$tag])) {
            $tag = $this->aliases[$tag];
        }

        if (isset($this->definitions[$tag])) {
            return $this->definitions[$tag];
        }

        foreach ($this->prefixes as $prefix) {
            if (\str_starts_with($tag, $prefix)) {
                $nonPrefixed = \substr($tag, \strlen($prefix));

                if (isset($this->definitions[$nonPrefixed])) {
                    return $this->definitions[$nonPrefixed];
                }
            }
        }

        return $this->parent?->findByName($tag);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->definitions;

        if ($this->parent !== null) {
            yield from $this->parent;
        }
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        $count = $this->parent === null ? 0 : \count($this->parent);

        return \count($this->definitions) + $count;
    }
}
