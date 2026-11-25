<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Tag\GenericTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\TagFactoryInterface;
use TypeLang\PhpDoc\TagRegistryInterface;

/**
 * Builds a tag from its name and suffix using the matching
 * {@see TagDefinitionInterface}.
 *
 * A tag with no registered definition is read by the definition created for
 * its name, a {@see GenericTagDefinition} by default: a plain {@see Tag} whose
 * whole suffix is its description.
 *
 * ```
 * $factory = new TagFactory(
 *     registry: (new TagRegistryBuilder(['link' => new LinkTagDefinition()]))
 *         ->build(),
 *     combinators: $combinators,
 * );
 * ```
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final class TagFactory implements TagFactoryInterface
{
    private readonly TagSpecificationParser $parser;

    /**
     * @var \Closure(non-empty-lowercase-string): TagDefinitionInterface
     */
    private readonly \Closure $createUnknownTagDefinition;

    /**
     * @param iterable<non-empty-string, CombinatorType> $combinators
     * @param (\Closure(
     *     non-empty-lowercase-string,
     * ): TagDefinitionInterface)|null $createUnknownTagDefinition
     *        creates the definition of a tag missing in the registry by its
     *        lower-case name, {@see GenericTagDefinition} by default
     */
    public function __construct(
        private readonly TagRegistryInterface $registry,
        iterable $combinators = [],
        ?\Closure $createUnknownTagDefinition = null,
    ) {
        $this->parser = new TagSpecificationParser($combinators);
        $this->createUnknownTagDefinition = $createUnknownTagDefinition
            ?? self::createGenericTagDefinition(...);
    }

    /**
     * @param non-empty-lowercase-string $name
     */
    private static function createGenericTagDefinition(string $name): TagDefinitionInterface
    {
        return new GenericTagDefinition($name);
    }

    public function create(string $name, string $suffix): TagInterface
    {
        $definition = $this->registry->find($name)
            ?? ($this->createUnknownTagDefinition)(\strtolower($name));

        try {
            $result = $this->parser->parse($definition, $name, $suffix);
        } catch (\Throwable $e) {
            return new InvalidTag($e, $name, Description::createIfNotEmpty($suffix));
        }

        return $definition->create($name, $result);
    }
}
