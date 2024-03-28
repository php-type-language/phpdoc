<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @template-implements \IteratorAggregate<array-key, Tag>
 */
class Description implements \Stringable, TagProviderInterface, \IteratorAggregate
{
    use TagProvider;

    private readonly string $template;

    /**
     * @param iterable<array-key, Tag> $tags
     */
    public function __construct(
        string|\Stringable $template = '',
        iterable $tags = [],
    ) {
        $this->template = (string) $template;

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
    public function getTemplate(): string
    {
        return $this->template;
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
