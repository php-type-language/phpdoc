<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a variable.
 */
final readonly class VariableReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the referenced variable, without the leading "$".
         *
         * @var non-empty-string
         */
        public string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('$%s', $this->name);
    }
}
