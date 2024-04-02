<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Tag\Description;
use TypeLang\PHPDoc\Tag\DescriptionInterface;
use TypeLang\PHPDoc\Tag\DescriptionProviderInterface;
use TypeLang\PHPDoc\Tag\OptionalDescriptionProviderInterface;
use TypeLang\PHPDoc\Tag\TagInterface;
use TypeLang\PHPDoc\Tag\TagsProvider;
use TypeLang\PHPDoc\Tag\TagsProviderInterface;

/**
 * This class represents structure containing a description and a set of tags
 * that describe an arbitrary DocBlock Comment in the code.
 *
 * @template-implements \IteratorAggregate<int<0, max>, TagInterface>
 * @template-implements \ArrayAccess<int<0, max>, TagInterface|null>
 */
final class DocBlock implements
    OptionalDescriptionProviderInterface,
    TagsProviderInterface,
    \IteratorAggregate,
    \ArrayAccess
{
    use TagsProvider;

    private readonly DescriptionInterface $description;

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

        $this->bootTagProvider($tags);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->tags[$offset]);
    }

    public function offsetGet(mixed $offset): ?TagInterface
    {
        return $this->tags[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException(self::class . ' objects are immutable');
    }

    public function getDescription(): DescriptionInterface
    {
        return $this->description;
    }
}
