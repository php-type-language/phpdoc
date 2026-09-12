<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a property of whatever a variable holds.
 *
 * ```
 *  $this->items
 * ```
 *
 * The property is the one of the object the variable holds rather than the one
 * a class declares, which is what a {@see ClassPropertyReference} points to.
 */
final class VariablePropertyReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the variable holding the object, without the leading "$".
         *
         * @var non-empty-string
         */
        public readonly string $variable,
        /**
         * The name of the referenced property.
         *
         * @var non-empty-string
         */
        public readonly string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('$%s->%s', $this->variable, $this->name);
    }
}
