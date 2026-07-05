<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a function.
 */
final readonly class FunctionReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the referenced function.
         *
         * @var non-empty-string
         */
        public string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('%s()', $this->name);
    }
}
