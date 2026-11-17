<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * What the platform of a package registers.
 */
final class LinkedPackage
{
    public function __construct(
        public readonly Package $package,
        /**
         * Registered names (the canonical names of the tags of the package and
         * the names of its aliases to other packages) to the tag whose
         * definition each of them gets, in the order of the names.
         *
         * @var array<non-empty-string, Tag>
         */
        public readonly array $tags,
        /**
         * Runtime aliases to the names registered by the package itself, in
         * the order of the names.
         *
         * @var array<non-empty-string, non-empty-string>
         */
        public readonly array $aliases,
        /**
         * Combinators the package contributes, in the order of their names.
         *
         * @var list<Combinator>
         */
        public readonly array $combinators,
    ) {}
}
