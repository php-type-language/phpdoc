<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

/**
 * How the source directory differs from the generated files.
 */
final class GeneratorDiff
{
    public function __construct(
        /**
         * Generated files missing in the directory or differing from it.
         *
         * @var list<non-empty-string>
         */
        public readonly array $written,
        /**
         * Generated files in the directory the model no longer produces.
         *
         * @var list<non-empty-string>
         */
        public readonly array $stale,
    ) {}

    public function isEmpty(): bool
    {
        return $this->written === [] && $this->stale === [];
    }
}
