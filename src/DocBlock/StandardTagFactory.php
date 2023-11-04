<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\DocBlock\Tag\GenericTag;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

/**
 * @template-implements TagFactoryInterface<TagInterface>
 */
final class StandardTagFactory implements TagFactoryInterface
{
    private readonly TagPartsExtractor $parts;

    /**
     * @var list<non-empty-lowercase-string>
     */
    private array $prefixes = [];

    /**
     * @var array<non-empty-lowercase-string, TagFactoryInterface>
     */
    private array $factories = [];

    /**
     * @var array<non-empty-lowercase-string, list<non-empty-lowercase-string>>
     */
    private array $mappings = [];

    /**
     * @param iterable<non-empty-string, TagFactoryInterface> $factories
     * @param iterable<array-key, non-empty-string> $prefixes
     */
    public function __construct(
        iterable $factories = [],
        iterable $prefixes = [],
    ) {
        $this->parts = new TagPartsExtractor();

        $this->addPrefix(...$prefixes);

        foreach ($factories as $name => $factory) {
            $this->add($factory, $name);
        }
    }

    /**
     * @param non-empty-string ...$prefix
     */
    public function addPrefix(string ...$prefix): void
    {
        foreach ($prefix as $item) {
            $this->prefixes[] = \strtolower($item);
        }
    }

    /**
     * @param non-empty-string ...$name
     */
    public function add(TagFactoryInterface $factory, string ...$name): void
    {
        foreach ($name as $item) {
            $this->factories[\strtolower($item)] = $factory;
        }
    }

    /**
     * @throws \Throwable
     */
    public function create(string $tag): TagInterface
    {
        [$name, $body] = $this->parts->extract($tag);

        if ($factory = $this->getFactory($name)) {
            return $factory->create($body ?? '');
        }

        if ($body === null) {
            return new GenericTag($name);
        }

        return new GenericTag($name, $body);
    }

    private function getFactory(string $tag): ?TagFactoryInterface
    {
        foreach ($this->getTagMappings($tag) as $variant) {
            if (isset($this->factories[$variant])) {
                return $this->factories[$variant];
            }
        }

        return null;
    }

    /**
     * @return list<non-empty-lowercase-string>
     */
    private function getTagMappings(string $tag): array
    {
        if ($tag === '') {
            return [];
        }

        $lower = \strtolower($tag);

        return $this->mappings[$lower] ??= [...$this->getTagVariants($lower)];
    }

    /**
     * @param lowercase-string $tag
     * @return iterable<array-key, non-empty-lowercase-string>
     */
    private function getTagVariants(string $tag): iterable
    {
        if ($tag === '') {
            return;
        }

        yield $tag;

        foreach ($this->prefixes as $prefix) {
            if (\str_starts_with($tag, $prefix . '-')) {
                $result = \substr($tag, \strlen($prefix) + 1);

                if ($result !== '') {
                    yield $result;
                }
            }
        }
    }
}
