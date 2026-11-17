<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Model;

/**
 * A family of tags understood by one tool, a platform class is generated for
 * each package.
 */
final class Package
{
    public function __construct(
        /**
         * Identifier of the package referenced by tags and aliases.
         *
         * @var non-empty-string
         */
        public readonly string $name,
        /**
         * Fully qualified name of the platform class.
         *
         * @var non-empty-string
         */
        public readonly string $class,
        /**
         * Human-readable name of the tool.
         *
         * @var non-empty-string
         */
        public readonly string $title,
        /**
         * Text of the docblock of the platform class.
         */
        public readonly string $description,
        /**
         * Model file the package is declared in.
         *
         * @var non-empty-string
         */
        public readonly string $file,
    ) {}
}
