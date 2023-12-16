<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagNameExtractor;
use TypeLang\PhpDocParser\DocBlock\Tag\GenericTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InvalidTypedTag;
use TypeLang\PhpDocParser\DocBlock\TagFactory\TagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagVariableNameException;

/**
 * @template-extends TagFactory<TagInterface>
 */
final class TagFactorySelector extends TagFactory
{
    private readonly TagNameExtractor $parts;

    /**
     * @var array<non-empty-lowercase-string, TagFactoryInterface>
     */
    private array $factories = [];

    /**
     * @param iterable<non-empty-string, TagFactoryInterface> $factories
     */
    public function __construct(
        iterable $factories = [],
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->parts = new TagNameExtractor();

        foreach ($factories as $name => $factory) {
            $this->add($factory, $name);
        }

        parent::__construct($descriptions);
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

        $factory = $this->getFactory($name);
        if ($factory !== null) {
            $body ??= '';

            try {
                return $factory->create($body);
            } catch (InvalidTagVariableNameException $e) {
                $type = $e->getType();
                $body = $this->createDescription(\substr($body, $e->getTypeOffset()));

                if ($type === null) {
                    return new InvalidTag($name, $e, $body);
                }

                return new InvalidTypedTag($name, $type, $e, $body);
            } catch (\InvalidArgumentException $e) {
                return new InvalidTag($name, $e, $this->createDescription($body));
            }
        }

        $name = \strtolower($name);

        if ($body === null) {
            return new GenericTag($name);
        }

        return new GenericTag(
            name: $name,
            description: $this->createDescription($body),
        );
    }

    private function getFactory(string $tag): ?TagFactoryInterface
    {
        return $this->factories[\strtolower($tag)] ?? null;
    }
}
