<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Description;

use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;

/**
 * @template-implements \ArrayAccess<array-key, TagInterface|null>
 * @template-implements \IteratorAggregate<array-key, TagInterface>
 */
class TaggedDescription implements
    TaggedDescriptionInterface,
    \IteratorAggregate,
    \ArrayAccess
{
    /**
     * @var list<TagInterface|DescriptionInterface>
     */
    public readonly array $components;

    /**
     * @var list<TagInterface>
     */
    public readonly array $tags;

    /**
     * @param iterable<array-key, TagInterface|DescriptionInterface> $components
     */
    public function __construct(iterable $components = [])
    {
        $this->components = \array_values([...$components]);

        $this->tags = $this->getTagsFromComponents($this->components);
    }

    /**
     * @param iterable<mixed, object> $components
     * @return list<TagInterface>
     */
    private function getTagsFromComponents(iterable $components): array
    {
        $result = [];

        foreach ($components as $component) {
            if ($component instanceof TagInterface) {
                $result[] = $component;
            }
        }

        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->tags[$offset]);
    }

    public function offsetGet(mixed $offset): ?TagInterface
    {
        return $this->tags[$offset] ?? null;
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

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->tags);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->tags);
    }

    public function __toString(): string
    {
        $result = [];

        foreach ($this->components as $component) {
            $result[] = $component instanceof TagInterface
                ? \sprintf('{%s}', $component)
                : $component;
        }

        return \implode('', $result);
    }
}
