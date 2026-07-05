<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PropertyTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * A magic property that a class exposes for reading, writing, or both.
 */
abstract class MagicPropertyTagDefinition extends TagDefinition
{
    /**
     * @param non-empty-string $name canonical name of the concrete tag
     */
    public function __construct(string $name)
    {
        parent::__construct(
            name: $name,
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            isInline: false,
        );
    }

    final public function create(string $name, TagPayload $result): MagicPropertyTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var non-empty-string $variable */
        $variable = $result->get('variable');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return $this->make($name, $type, $variable, $description);
    }

    /**
     * @param non-empty-string $variable
     */
    abstract protected function make(
        string $name,
        TypeReference $type,
        string $variable,
        ?DescriptionInterface $description,
    ): MagicPropertyTag;
}
