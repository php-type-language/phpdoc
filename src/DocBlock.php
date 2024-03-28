<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\Description\Description;
use TypeLang\PHPDoc\Tag\Description\DescriptionInterface;
use TypeLang\PHPDoc\Tag\TagInterface;
use TypeLang\PHPDoc\Tag\TagProvider;
use TypeLang\PHPDoc\Tag\TagProviderInterface;

/**
 * @template-implements \IteratorAggregate<array-key, TagInterface>
 */
final class DocBlock implements
    TagProviderInterface,
    SerializableInterface,
    \IteratorAggregate
{
    use TagProvider;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        private readonly DescriptionInterface $description = new Description(),
        iterable $tags = [],
    ) {
        $this->bootTagProvider($tags);
    }

    /**
     * @return array{
     *     description: array<array-key, mixed>,
     *     tags: list<array<array-key, mixed>>
     * }
     */
    public function toArray(): array
    {
        $tags = [];

        foreach ($this->tags as $tag) {
            $tags[] = $tag->toArray();
        }

        return [
            'description' => $this->description->toArray(),
            'tags' => $tags,
        ];
    }

    /**
     * @return array{
     *     description: array<array-key, mixed>,
     *     tags: list<array<array-key, mixed>>
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
