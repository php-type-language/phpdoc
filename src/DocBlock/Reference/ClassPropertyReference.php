<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::$property" format.
 */
final class ClassPropertyReference extends Reference
{
    public function __construct(
        public readonly Name $class,
        public readonly VariableLiteralNode $property,
    ) {}

    /**
     * @return array{
     *     kind: int<0, max>,
     *     class: class-string,
     *     property: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::CLASS_PROPERTY_KIND,
            'class' => $this->class->toString(),
            'property' => $this->property->getValue(),
        ];
    }

    public function __toString(): string
    {
        return \vsprintf('%s::%s', [
            $this->class->toString(),
            $this->property->getRawValue(),
        ]);
    }
}
