<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;

interface MutableRepositoryInterface extends RepositoryInterface
{
    /**
     * Adds a tag definition to the repository.
     */
    public function add(DefinitionInterface $definition): void;

    /**
     * Adds a new tag alias.
     *
     * ```php
     *  $repository->alias('psalm-var', 'var');
     * ```
     */
    public function alias(string $alias, string $tag): void;

    /**
     * Adds a new global tag prefix.
     *
     * ```php
     *  $repository->prefix('psalm');
     * ```
     */
    public function prefix(string $prefix): void;
}
