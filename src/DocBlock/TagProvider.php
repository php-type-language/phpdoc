<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock;

use TypeLang\Reader\DocBlock\Tag\TagInterface;

/**
 * @psalm-require-implements TagProviderInterface
 */
trait TagProvider
{
    /**
     * @var list<TagInterface>
     */
    private readonly array $tags;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    protected function initializeTags(iterable $tags): void
    {
        $this->tags = \array_values([...$tags]);
    }

    /**
     * Returns the tags for this DocBlock.
     *
     * @return list<TagInterface>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return \Traversable<array-key, TagInterface>
     */
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
