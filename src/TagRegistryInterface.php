<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * @template-extends \Traversable<non-empty-lowercase-string, TagDefinitionInterface>
 */
interface TagRegistryInterface extends \Traversable, \Countable
{
    /**
     * @param non-empty-string $name
     */
    public function get(string $name): TagDefinitionInterface;

    /**
     * @param non-empty-string $name
     */
    public function has(string $name): bool;

    /**
     * @return int<0, max>
     */
    public function count(): int;
}
