<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Tag;

final class Factory implements MutableFactoryInterface
{
    /**
     * @param array<non-empty-string, FactoryInterface> $factories
     */
    public function __construct(
        private array $factories = [],
    ) {}

    public function add(array|string $tags, FactoryInterface $delegate): void
    {
        foreach ((array) $tags as $tag) {
            $this->factories[$tag] = $delegate;
        }
    }

    public function create(string $name, string $content, DescriptionParserInterface $descriptions): Tag
    {
        $delegate = $this->factories[$name] ?? null;

        if ($delegate !== null) {
            return $delegate->create($name, $content, $descriptions);
        }

        return new Tag($name, $descriptions->parse($content));
    }
}
