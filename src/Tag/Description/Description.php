<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

use TypeLang\Parser\Node\SerializableInterface;
use TypeLang\PHPDoc\Tag\TagInterface;
use TypeLang\PHPDoc\Tag\TagProvider;
use TypeLang\PHPDoc\Tag\TagProviderInterface;

/**
 * @template-implements \IteratorAggregate<array-key, TagInterface>
 */
final class Description implements DescriptionInterface, TagProviderInterface, \IteratorAggregate
{
    use TagProvider;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        private readonly string|\Stringable $template = '',
        iterable $tags = [],
    ) {
        $this->bootTagProvider($tags);
    }

    public static function fromString(string|\Stringable $description): self
    {
        if ($description instanceof self) {
            return $description;
        }

        return new self($description);
    }

    public static function fromStringOrNull(string|\Stringable|null $description): ?self
    {
        if ($description === null) {
            return null;
        }

        return self::fromString($description);
    }

    /**
     * Returns the body template.
     *
     * @api
     * @psalm-immutable
     */
    public function getTemplate(): string|\Stringable
    {
        return $this->template;
    }

    /**
     * @return array{
     *     template: array|string,
     *     tags: list<array>
     * }
     */
    public function toArray(): array
    {
        $tags = [];

        foreach ($this->tags as $tag) {
            $tags[] = $tag->toArray();
        }

        $template = $this->template;

        return [
            'template' => match (true) {
                $template instanceof SerializableInterface => $template->toArray(),
                default => (string) $template,
            },
            'tags' => $tags,
        ];
    }

    /**
     * @return array{
     *     template: array|string,
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
        $tags = [];

        foreach ($this->tags as $tag) {
            $tags[] = (string) $tag;
        }

        return \vsprintf((string) $this->template, $tags);
    }
}
