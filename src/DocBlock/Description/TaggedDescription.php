<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Description;

use TypeLang\PhpDoc\DocBlock\ComponentInterface;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;

/**
 * Any class that implements this interface is a description object
 * containing an arbitrary set of nested tags ({@see TagInterface}).
 *
 * @template-implements \ArrayAccess<array-key, ComponentInterface>
 * @template-implements \IteratorAggregate<array-key, ComponentInterface>
 */
final class TaggedDescription implements
    DescriptionInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    /**
     * Gets a list of {@see ComponentInterface} components that make up the
     * {@see TaggedDescriptionInterface} in the order in which these
     * elements are defined.
     *
     * @var list<ComponentInterface>
     */
    public readonly array $components;

    /**
     * Gets a list of all tags within a description
     *
     * @var list<TagInterface>
     */
    public array $tags {
        get => $this->tags ??= $this->only(TagInterface::class);
    }

    /**
     * @param iterable<mixed, ComponentInterface> $components
     */
    public function __construct(iterable $components = [])
    {
        $this->components = \iterator_to_array($components, false);
    }

    /**
     * @template TArgComponent of ComponentInterface
     * @param class-string<TArgComponent> $component
     * @return list<TArgComponent>
     */
    public function only(string $component): array
    {
        $result = [];

        foreach ($this->components as $actual) {
            if ($actual instanceof $component) {
                $result[] = $actual;
            }
        }

        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->components[$offset]);
    }

    public function offsetGet(mixed $offset): ?ComponentInterface
    {
        return $this->components[$offset] ?? null;
    }

    /**
     * @throws \BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * @throws \BadMethodCallException
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
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

    public function __toString(): string
    {
        $result = [];

        foreach ($this->components as $actual) {
            $result[] = match (true) {
                $actual instanceof TagInterface => \sprintf('{%s}', $actual),
                default => $actual,
            };
        }

        return \implode('', $result);
    }
}
