<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

/**
 * @template-implements \IteratorAggregate<int<0, max>, TagInterface>
 */
class Description implements DescriptionInterface, \IteratorAggregate
{
    use TagsProvider;

    protected readonly string $template;

    /**
     * @param iterable<array-key, TagInterface> $tags
     */
    public function __construct(
        string|\Stringable $template = '',
        iterable $tags = [],
    ) {
        $this->template = (string) $template;

        $this->bootTagProvider($tags);
    }

    /**
     * @return ($description is DescriptionInterface ? DescriptionInterface : self)
     */
    public static function fromStringable(string|\Stringable $description): DescriptionInterface
    {
        if ($description instanceof DescriptionInterface) {
            return $description;
        }

        return new self($description);
    }

    /**
     * @return ($description is DescriptionInterface ? DescriptionInterface : self|null)
     */
    public static function fromStringableOrNull(string|\Stringable|null $description): ?DescriptionInterface
    {
        if ($description === null) {
            return null;
        }

        return self::fromStringable($description);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function __toString(): string
    {
        $tags = [];

        foreach ($this->tags as $tag) {
            $tags[] = (string) $tag;
        }

        return \vsprintf($this->template, $tags);
    }
}
