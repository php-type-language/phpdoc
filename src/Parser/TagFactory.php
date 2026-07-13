<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\TagFactoryInterface;
use TypeLang\PhpDoc\TagRegistryInterface;

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
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final readonly class TagFactory implements TagFactoryInterface
{
    private TagSpecificationParser $parser;

    /**
     * @param iterable<non-empty-string, CombinatorType> $combinators
     */
    public function __construct(
        private TagRegistryInterface $registry,
        iterable $combinators = [],
    ) {
        $this->parser = new TagSpecificationParser($combinators);
    }

    public function create(string $name, string $suffix): TagInterface
    {
        $definition = $this->registry->get($name);

        try {
            $result = $this->parser->parse($definition, $name, $suffix);
        } catch (\Throwable $e) {
            return new InvalidTag($e, $name, Description::createIfNotEmpty($suffix));
        }

        return $definition->create($name, $result);
    }
}
