<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * A tag that checks the inferred type of a variable against an expected type.
 */
abstract class CheckTypeTagDefinition extends TagDefinition
{
    /**
     * @param non-empty-string $name canonical name of the concrete tag
     */
    public function __construct(string $name)
    {
        parent::__construct(
            name: $name,
            spec: Spec::sequence(
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::literal('='),
                Spec::rule(TypeCombinator::NAME, 'type'),
            ),
            placement: TagPlacement::Block,
        );
    }

    final public function create(string $name, TagPayload $result): CheckTypeTag
    {
        /** @var non-empty-string $variable */
        $variable = $result->get('variable');

        /** @var TypeReference $type */
        $type = $result->get('type');

        return $this->make($name, $variable, $type);
    }

    /**
     * @param non-empty-string $variable
     */
    abstract protected function make(
        string $name,
        string $variable,
        TypeReference $type,
    ): CheckTypeTag;
}
