<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

/**
 * @psalm-require-implements TagProviderInterface
 */
trait TagProvider
{
    /**
     * @var list<TagInterface>
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private readonly array $tags;

    /**
     * @param iterable<array-key, TagInterface> $tags
     * @psalm-suppress InaccessibleProperty
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
        foreach ($this->tags as $tag) {
            yield $tag;

            $description = $tag->getDescription();

            if ($description instanceof TagProviderInterface) {
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
