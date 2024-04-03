<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Factory;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Content;
use TypeLang\PHPDoc\Tag\TagInterface;

final class PrefixedTagFactory implements MutableFactoryInterface
{
    /**
     * @param non-empty-list<non-empty-string> $prefixes
     */
    public function __construct(
        private readonly array $prefixes,
        private readonly MutableFactoryInterface $delegate = new TagFactory(),
        private readonly bool $allowNonPrefixedTags = true,
    ) {}

    public function register(array|string $tags, FactoryInterface $delegate): void
    {
        foreach ((array) $tags as $tag) {
            if ($this->allowNonPrefixedTags) {
                $this->delegate->register($tag, $delegate);
            }

            foreach ($this->prefixes as $prefix) {
                $this->delegate->register($prefix . $tag, $delegate);
            }
        }
    }

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): TagInterface
    {
        return $this->delegate->create($name, $content, $descriptions);
    }
}
