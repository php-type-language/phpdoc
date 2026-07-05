<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a method of a class.
 */
final readonly class ClassMethodReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the class that declares the referenced method.
         *
         * @var non-empty-string
         */
        public string $class,
        /**
         * The name of the referenced method.
         *
         * @var non-empty-string
         */
        public string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('%s::%s()', $this->class, $this->name);
    }
}
