<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * Everything the model files declare, as written: not yet checked or linked.
 */
final class Model
{
    public function __construct(
        /**
         * @var list<Package>
         */
        public readonly array $packages = [],
        /**
         * @var list<Combinator>
         */
        public readonly array $combinators = [],
        /**
         * @var list<Tag>
         */
        public readonly array $tags = [],
        /**
         * @var list<Alias>
         */
        public readonly array $aliases = [],
    ) {}
}
