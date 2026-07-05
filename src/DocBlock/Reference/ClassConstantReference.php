<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to a constant of a class.
 */
final readonly class ClassConstantReference extends CodeReference
{
    public function __construct(
        /**
         * The name of the class that declares the referenced constant.
         *
         * @var non-empty-string
         */
        public string $class,
        /**
         * The name of the referenced constant.
         *
         * @var non-empty-string
         */
        public string $name,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return \sprintf('%s::%s', $this->class, $this->name);
    }
}
