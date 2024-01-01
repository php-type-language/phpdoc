<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Description;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Parser\DocBlock\TagProvider;
use TypeLang\PhpDoc\Parser\DocBlock\TagProviderInterface;

final class DocBlock implements TagProviderInterface, SerializableInterface
{
    use TagProvider {
        getIterator as private getTagProviderIterator;
    }

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        private readonly Description $description = new Description(),
        iterable $tags = [],
    ) {
        $this->initializeTags($tags);
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @return array{
     *     description: array{
     *         template: string,
     *         tags: list<array>
     *     },
     *     tags: list<array>
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
     *     description: array{
     *         template: string,
     *         tags: list<array>
     *     },
     *     tags: list<array>
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getIterator(): \Traversable
    {
        yield from $this->description;
        yield from $this->getTagProviderIterator();
    }
}
