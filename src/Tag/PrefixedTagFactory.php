<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

final class PrefixedTagFactory implements MutableFactoryInterface
{
    /**
     * @param non-empty-list<non-empty-string> $prefixes
     */
    public function __construct(
        private readonly array $prefixes,
        private readonly MutableFactoryInterface $delegate = new TagFactory(),
    ) {}

    public function register(array|string $tags, FactoryInterface $delegate): void
    {
        foreach ($this->prefixes as $prefix) {
            foreach ((array) $tags as $tag) {
                $this->delegate->register($prefix . $tag, $delegate);
            }
        }
    }

    public function create(string $name, string $content, DescriptionParserInterface $descriptions): Tag
    {
        return $this->delegate->create($name, $content, $descriptions);
    }
}
