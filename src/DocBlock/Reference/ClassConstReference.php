<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::CONST" format.
 */
final class ClassConstReference extends Reference
{
    public function __construct(
        public readonly Name $class,
        public readonly Identifier $const,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     class: class-string,
     *     const: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::CLASS_CONST_KIND,
            'class' => $this->class->toString(),
            'const' => $this->const->toString(),
        ];
    }

    public function __toString(): string
    {
        return \vsprintf('%s::%s', [
            $this->class->toString(),
            $this->const->toString(),
        ]);
    }
}
