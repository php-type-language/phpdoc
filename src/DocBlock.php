<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlock\TagProvider;
use TypeLang\PhpDocParser\DocBlock\TagProviderInterface;

final class DocBlock implements TagProviderInterface
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

    public function getIterator(): \Traversable
    {
        yield from $this->description;
        yield from $this->getTagProviderIterator();
    }
}
