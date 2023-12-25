<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

final class Description implements
    TagProviderInterface,
    SerializableInterface,
    \Stringable
{
    use TagProvider;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        private readonly string $template = '',
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
     *
     * @psalm-immutable
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return array{
     *     template: string,
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
            'template' => $this->template,
            'tags' => $tags,
        ];
    }

    /**
     * @return array{
     *     template: string,
     *     tags: list<array>
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @psalm-immutable
     */
    public function __toString(): string
    {
        /** @psalm-suppress ImplicitToStringCast */
        return \vsprintf($this->template, $this->tags);
    }
}
