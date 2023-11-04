<?php

declare(strict_types=1);

namespace TypeLang\Reader;

use TypeLang\Reader\DocBlock\Description;
use TypeLang\Reader\DocBlock\Tag\TagInterface;
use TypeLang\Reader\DocBlock\TagProvider;
use TypeLang\Reader\DocBlock\TagProviderInterface;

final class DocBlock implements TagProviderInterface, \Stringable
{
    use TagProvider;

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

    public function __toString(): string
    {
        $result = $this->description . "\n";

        foreach ($this->tags as $tag) {
            $result .= $tag . "\n";
        }

        return $result;
    }
}
