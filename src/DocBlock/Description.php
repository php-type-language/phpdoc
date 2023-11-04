<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock;

use TypeLang\Reader\DocBlock\Tag\TagInterface;

final class Description implements TagProviderInterface, \Stringable
{
    use TagProvider;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        private readonly string $bodyTemplate = '',
        iterable $tags = [],
    ) {
        $this->initializeTags($tags);
    }

    /**
     * Returns the body template.
     */
    public function getBodyTemplate(): string
    {
        return $this->bodyTemplate;
    }

    public function __toString(): string
    {
        return \vsprintf($this->bodyTemplate, $this->tags);
    }
}
