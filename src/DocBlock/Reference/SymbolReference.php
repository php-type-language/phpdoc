<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a named symbol, such as a class, interface, trait, enum
 * or a global constant.
 */
final readonly class SymbolReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the referenced symbol.
         *
         * @var non-empty-string
         */
        public string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('%s', $this->name);
    }
}
