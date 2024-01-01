<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock;

use TypeLang\PhpDoc\Parser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\TagNameReader;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\GenericTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\InvalidTypedTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\TagFactory;
use TypeLang\PhpDoc\Parser\Exception\DocBlockExceptionInterface;
use TypeLang\PhpDoc\Parser\Exception\InvalidTypedTagExceptionInterface;

/**
 * @template-extends TagFactory<TagInterface>
 * @template-implements \IteratorAggregate<non-empty-string, TagFactoryInterface>
 */
final class Registry extends TagFactory implements RegistryInterface, \IteratorAggregate
{
    private readonly TagNameReader $names;

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
        $this->names = new TagNameReader();

        parent::__construct($descriptions);

        $this->load($factories);
    }

    /**
     * @param iterable<non-empty-string, TagFactoryInterface> $factories
     */
    private function load(iterable $factories): void
    {
        foreach ($factories as $name => $factory) {
            $this->add($factory, $name);
        }
    }

    public function add(TagFactoryInterface $factory, string $name, string ...$aliases): void
    {
        $this->factories[\strtolower($name)] = $factory;

        foreach ($aliases as $alias) {
            $this->factories[\strtolower($alias)] = $factory;
        }
    }

    /**
     * @throws \Throwable
     */
    public function create(string $content): TagInterface
    {
        $selection = $this->names->read($content);

        $name = \substr($selection->data, 1);
        $body = \ltrim(\substr($content, $selection->offset));
        $lower = \strtolower($name);

        if (($factory = $this->factories[$lower] ?? null) !== null) {
            return $this->createFromFactory($factory, $name, $body);
        }

        if ($body === '') {
            return new GenericTag($name);
        }

        return new GenericTag($name, $this->createDescription($body));
    }

    /**
     * @param non-empty-lowercase-string $name
     */
    private function createFromFactory(TagFactoryInterface $factory, string $name, string $body): TagInterface
    {
        try {
            return $factory->create($body);
        } catch (InvalidTypedTagExceptionInterface $e) {
            $type = $e->getType();
            $suffix = $this->createDescription(\substr($body, $e->getTypeOffset()));

            if ($type === null) {
                return new InvalidTag($name, $e, $suffix);
            }

            return new InvalidTypedTag($name, $type, $e, $suffix);
        } catch (DocBlockExceptionInterface $e) {
            return new InvalidTag($name, $e, $this->createDescription($body));
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->factories);
    }

    public function count(): int
    {
        return \count($this->factories);
    }

    public function has(string $name): bool
    {
        return isset($this->factories[\strtolower($name)]);
    }
}
