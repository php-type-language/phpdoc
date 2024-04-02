<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Tag\Description;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\TagProvider;
use TypeLang\PHPDoc\Tag\TagProviderInterface;

/**
 * @template-implements \IteratorAggregate<int<0, max>, Tag>
 * @template-implements \ArrayAccess<int<0, max>, Tag|null>
 */
final class DocBlock implements
    TagProviderInterface,
    \IteratorAggregate,
    \ArrayAccess
{
    use TagProvider;

    /**
     * @param iterable<array-key, Tag> $tags
     */
    public function __construct(
        private readonly Description $description = new Description(),
        iterable $tags = [],
    ) {
        $this->bootTagProvider($tags);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->tags[$offset]);
    }

    public function offsetGet(mixed $offset): ?Tag
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

    public function getDescription(): Description
    {
        return $this->description;
    }
}
