<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

use TypeLang\Type\TypeNode;

/**
 * A parsed type together with the exact source text it was read from.
 */
final readonly class TypeReference implements ReferenceInterface
{
    public bool $isExternal;

    public function __construct(
        public TypeNode $type,
        /**
         * The original type text, exactly as it appeared in the source.
         *
         * @var non-empty-string
         */
        public string $source,
    ) {
        $this->isExternal = false;
    }

    public function __toString(): string
    {
        return $this->source;
    }
}
