<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

/**
 * Representation of a reference in the case that the reference could not
 * be processed in any way.
 */
final class GenericReference extends Reference
{
    /**
     * @param non-empty-string $reference
     */
    public function __construct(
        public readonly string $reference,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     reference: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::GENERIC_KIND,
            'reference' => $this->reference,
        ];
    }

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
