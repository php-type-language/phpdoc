<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::foo()" format.
 */
final class MethodReference extends Reference
{
    public function __construct(
        public readonly Name $class,
        public readonly Identifier $method,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     class: class-string,
     *     method: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::CLASS_METHOD_KIND,
            'class' => $this->class->toString(),
            'method' => $this->method->toString(),
        ];
    }

    public function __toString(): string
    {
        return \vsprintf('%s::%s()', [
            $this->class->toString(),
            $this->method->toString(),
        ]);
    }
}
