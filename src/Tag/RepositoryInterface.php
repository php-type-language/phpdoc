<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;

/**
 * @template-extends \Traversable<array-key, DefinitionInterface>
 */
interface RepositoryInterface extends \Traversable, \Countable
{
    /**
     * Returns tag definition by name.
     *
     * @param non-empty-string $tag
     */
    public function findByName(string $tag): ?DefinitionInterface;

    /**
     * @return int<0, max>
     */
    public function count(): int;
}
