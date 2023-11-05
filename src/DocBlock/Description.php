<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

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

    public static function create(string $body): self
    {
        return new self($body);
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
        /** @psalm-suppress ImplicitToStringCast */
        return \vsprintf($this->bodyTemplate, $this->tags);
    }
}
