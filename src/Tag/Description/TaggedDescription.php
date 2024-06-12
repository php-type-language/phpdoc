<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

use TypeLang\PHPDoc\Tag\TagInterface;

/**
 * @template-implements \ArrayAccess<array-key, TagInterface|DescriptionInterface|null>
 * @template-implements \IteratorAggregate<array-key, TagInterface|DescriptionInterface>
 */
class TaggedDescription implements
    TaggedDescriptionInterface,
    \IteratorAggregate,
    \ArrayAccess
{
    /**
     * @var list<TagInterface|DescriptionInterface>
     */
    private readonly array $components;

    /**
     * @param iterable<array-key, TagInterface|DescriptionInterface> $components
     */
    public function __construct(iterable $components = [])
    {
        $this->components = \array_values([...$components]);
    }

    /**
     * @return list<TagInterface|DescriptionInterface>
     */
    public function getComponents(): iterable
    {
        return $this->components;
    }

    public function getTags(): iterable
    {
        foreach ($this->components as $component) {
            if ($component instanceof TagInterface) {
                yield $component;
            }
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->components);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->components);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->components[$offset]);
    }

    public function offsetGet(mixed $offset): TagInterface|DescriptionInterface|null
    {
        return $this->components[$offset] ?? null;
    }

    /**
     * @throws \BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException(static::class . ' objects are immutable');
    }

    /**
     * @throws \BadMethodCallException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException(static::class . ' objects are immutable');
    }

    public function __toString(): string
    {
        return \implode('', $this->components);
    }
}
