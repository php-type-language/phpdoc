<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The registered tag definitions keyed by the tag name, the names of aliases
 * included.
 *
 * @template-extends \Traversable<non-empty-lowercase-string, TagDefinitionInterface>
 */
interface TagRegistryInterface extends \Traversable, \Countable
{
    /**
     * Finds the definition of the tag by its name (case-insensitive), or
     * returns {@see null} when the tag is not registered.
     *
     * @param non-empty-string $name
     */
    public function find(string $name): ?TagDefinitionInterface;

    /**
     * @param non-empty-string $name
     */
    public function has(string $name): bool;

    /**
     * @return int<0, max>
     */
    public function count(): int;
}
