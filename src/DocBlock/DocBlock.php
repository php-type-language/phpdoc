<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock;

use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PHPDoc\DocBlock\Description\OptionalDescriptionProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TagsProviderInterface;

/**
 * An implementation represents structure containing a description and a set
 * of tags that describe an arbitrary DocBlock Comment in the code.
 *
 * @template-implements \ArrayAccess<array-key, TagInterface|null>
 * @template-implements \IteratorAggregate<array-key, TagInterface>
 */
final class DocBlock implements
    OptionalDescriptionProviderInterface,
    TagsProviderInterface,
    \IteratorAggregate,
    \ArrayAccess,
    \Countable
{
    public readonly ?DescriptionInterface $description;

    /**
     * @var list<TagInterface>
     */
    public readonly array $tags;

    /**
     * @param iterable<array-key, TagInterface> $tags List of all tags contained in
     *        a docblock object.
     *
     *        Note that the constructor can receive an arbitrary iterator, like
     *        {@see \Traversable} or {@see array}, but the object itself
     *        contains the directly generated list ({@see array}} of
     *        {@see TagInterface} objects.
     */
    public function __construct(
        string|\Stringable $description = '',
        iterable $tags = [],
    ) {
        $this->description = Description::fromStringable($description);
        $this->tags = \array_values([...$tags]);
    }

    /**
     * @internal direct usage of {@see offsetExists()} is not recommended
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->tags[$offset]);
    }

    /**
     * @internal direct usage of {@see offsetGet()} is not recommended
     */
    public function offsetGet(mixed $offset): ?TagInterface
    {
        return $this->tags[$offset] ?? null;
    }

    /**
     * @internal direct usage of {@see offsetSet()} is not recommended
     * @throws \BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    /**
     * @internal direct usage of {@see offsetUnset()} is not recommended
     * @throws \BadMethodCallException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
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
}
