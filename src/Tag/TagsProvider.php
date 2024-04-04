<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @mixin TagsProviderInterface
 * @mixin \IteratorAggregate
 * @mixin \ArrayAccess
 *
 * @psalm-require-implements TagsProviderInterface
 * @psalm-require-implements \IteratorAggregate
 * @psalm-require-implements \ArrayAccess
 *
 * @internal This is an internal library trait, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc\Tag
 */
trait TagsProvider
{
    /**
     * @var list<TagInterface>
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected readonly array $tags;

    /**
     * @param iterable<array-key, TagInterface> $tags
     * @psalm-suppress InaccessibleProperty
     */
    protected function bootTagProvider(iterable $tags): void
    {
        $this->tags = \array_values([...$tags]);
    }

    /**
     * @see TagsProviderInterface::getTags()
     *
     * @return list<TagInterface>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function offsetExists(mixed $offset): bool
    {
        assert(\is_int($offset));

        return isset($this->tags[$offset]);
    }

    public function offsetGet(mixed $offset): ?TagInterface
    {
        assert(\is_int($offset));

        return $this->tags[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_int($offset));
        assert($value instanceof TagInterface);

        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \BadMethodCallException
     */
    public function offsetUnset(mixed $offset): void
    {
        assert(\is_int($offset));

        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * @return \Traversable<int<0, max>, TagInterface>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->tags as $tag) {
            yield $tag;

            $description = $tag->getDescription();

            if ($description instanceof TagsProviderInterface) {
                yield from $description;
            }
        }
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->tags);
    }
}
