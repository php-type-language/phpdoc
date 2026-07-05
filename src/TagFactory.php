<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Tag\GenericTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;
use TypeLang\PhpDoc\Parser\TagSpecificationParser;

/**
 * Builds a tag from its name and suffix using the matching
 * {@see TagDefinitionInterface}.
 *
 * A tag with no registered definition becomes a plain {@see Tag} whose whole
 * suffix is its description.
 *
 * ```
 * $factory = new TagFactory(
 *     definitions: ['link' => new LinkTagDefinition()],
 *     grammar: $grammar,
 * );
 * ```
 *
 * @phpstan-import-type CombinatorType from Grammar
 *
 * @template-implements \IteratorAggregate<non-empty-string, TagDefinitionInterface>
 */
final readonly class TagFactory implements TagFactoryInterface, \IteratorAggregate
{
    /**
     * @var array<non-empty-string, TagDefinitionInterface>
     */
    private array $definitions;

    /**
     * @var array<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    private array $aliases;

    private TagSpecificationParser $parser;

    /**
     * @param iterable<non-empty-lowercase-string, TagDefinitionInterface> $definitions
     * @param iterable<non-empty-lowercase-string, non-empty-lowercase-string> $aliases
     * @param iterable<non-empty-string, CombinatorType> $combinators
     */
    public function __construct(
        iterable $definitions = [],
        iterable $aliases = [],
        iterable $combinators = [],
        private TagDefinitionInterface $genericTagDefinition = new GenericTagDefinition(),
    ) {
        $this->parser = new TagSpecificationParser($combinators);
        $this->definitions = \iterator_to_array($definitions);
        $this->aliases = \iterator_to_array($aliases);
    }

    public function create(string $name, string $suffix): TagInterface
    {
        $definition = $this->get($name);

        try {
            $result = $this->parser->parse($definition, $name, $suffix);
        } catch (\Throwable $e) {
            return new InvalidTag($e, $name, Description::createIfNotEmpty($suffix));
        }

        return $definition->create($name, $result);
    }

    public function get(string $name): TagDefinitionInterface
    {
        // normalize
        $name = \strtolower($name);
        // canonicalize
        $name = $this->aliases[$name] ?? $name;

        return $this->definitions[$name]
            ?? $this->genericTagDefinition;
    }

    public function getIterator(): \Traversable
    {
        /** @var \ArrayIterator<non-empty-string, TagDefinitionInterface> */
        return new \ArrayIterator($this->definitions);
    }

    public function count(): int
    {
        return \count($this->definitions);
    }
}
