<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Representation of the "CONST", "Foo\Class" or something like that.
 */
final class NameReference extends Reference
{
    public function __construct(
        public readonly Name $name,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     name: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::NAME_KIND,
            'name' => $this->name->toString(),
        ];
    }

    public function __toString(): string
    {
        return $this->name->toString();
    }
}
