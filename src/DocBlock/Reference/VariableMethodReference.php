<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a method of whatever a variable holds.
 *
 * ```
 *  $this->getItems()
 * ```
 *
 * The method is the one of the object the variable holds rather than the one
 * a class declares, which is what a {@see ClassMethodReference} points to.
 */
final class VariableMethodReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the variable holding the object, without the leading "$".
         *
         * @var non-empty-string
         */
        public readonly string $variable,
        /**
         * The name of the referenced method.
         *
         * @var non-empty-string
         */
        public readonly string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('$%s->%s()', $this->variable, $this->name);
    }
}
