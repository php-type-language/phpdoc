<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * Another name of a tag (or of another alias).
 *
 * Within the package of its target the alias is a runtime alias of the
 * platform. In any other package it is registered as an entry of its own,
 * with the definition of the tag it finally points to.
 */
final class Alias
{
    public function __construct(
        /**
         * @var non-empty-string
         */
        public readonly string $name,
        /**
         * Name of the tag or of the alias it points to.
         *
         * @var non-empty-string
         */
        public readonly string $target,
        /**
         * Package registering the alias.
         *
         * @var non-empty-string
         */
        public readonly string $package,
        /**
         * @var list<Example>
         */
        public readonly array $examples,
        /**
         * Model file the alias is declared in.
         *
         * @var non-empty-string
         */
        public readonly string $file,
    ) {}
}
