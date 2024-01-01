<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock;

/**
 * @template-extends \Traversable<non-empty-string, TagFactoryInterface>
 */
interface RegistryInterface extends TagFactoryInterface, \Traversable, \Countable
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string ...$aliases
     */
    public function add(TagFactoryInterface $factory, string $name, string ...$aliases): void;

    /**
     * @param non-empty-string $name
     */
    public function has(string $name): bool;
}
