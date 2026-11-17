<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * A usage example of a tag: a docblock, possibly followed by the code it
 * documents.
 */
final class Example
{
    public function __construct(
        /**
         * One sentence describing what the example shows, {@see null} for an
         * example that speaks for itself.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $title,
        /**
         * PHP source of the example.
         */
        public readonly string $code,
    ) {}
}
