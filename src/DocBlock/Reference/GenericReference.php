<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

/**
 * Representation of a reference in the case that the reference could not
 * be processed in any way.
 */
final class GenericReference implements ReferenceInterface
{
    /**
     * @param non-empty-string $reference
     */
    public function __construct(
        public readonly string $reference,
    ) {}

    /**
     * @return non-empty-string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    public function __toString(): string
    {
        return $this->reference;
    }
}
