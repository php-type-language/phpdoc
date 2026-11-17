<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * A named terminal of the tag grammar, implemented by hand.
 *
 * A combinator without a package is provided by the parser itself to the tags
 * of every package.
 */
final class Combinator
{
    public function __construct(
        /**
         * Name the grammar references the combinator by.
         *
         * @var non-empty-string
         */
        public readonly string $name,
        /**
         * Fully qualified name of the implementation.
         *
         * @var non-empty-string
         */
        public readonly string $class,
        /**
         * Package contributing the combinator, {@see null} for the combinators
         * of the parser itself.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $package,
        /**
         * Whether the generated code creates the instance, the parser creates
         * the others itself.
         */
        public readonly bool $isInstanceGenerated,
        /**
         * Model file the combinator is declared in.
         *
         * @var non-empty-string
         */
        public readonly string $file,
    ) {}
}
