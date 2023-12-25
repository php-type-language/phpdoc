<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Representation of the "foo()" format.
 */
final class FunctionReference extends Reference
{
    public function __construct(
        public readonly Name $function,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     function: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::FUNCTION_KIND,
            'function' => $this->function->toString(),
        ];
    }

    public function __toString(): string
    {
        return $this->function->toString() . '()';
    }
}
