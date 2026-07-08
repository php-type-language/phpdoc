<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\MethodTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\Type\CallableTypeNode;

/**
 * The `@method` tag declares a "magic" method that can be called on the
 * described class, described as a callable carrying its name, parameters and
 * return type.
 */
final class MethodTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The method signature: its name, parameters and return type.
         */
        public readonly CallableTypeNode $method,
        /**
         * The signature exactly as written, kept for faithful rendering.
         */
        private readonly string $signature,
        public readonly bool $isStatic = false,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s', $this->name);

        if ($this->isStatic) {
            $result .= ' static';
        }

        $result .= ' ' . $this->signature;

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
