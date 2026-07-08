<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\VarTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@var` tag documents the type of a property, constant or inline
 * variable.
 *
 * ```
 * "@var" <Type> [ <Variable> ] [ <Description> ]
 * ```
 */
final class VarTagDefinition extends TagDefinition
{
    public const string NAME = 'var';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::maybe(
                    Spec::rule(VariableCombinator::NAME, 'variable'),
                ),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): VarTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var non-empty-string|null $variable */
        $variable = $result->find('variable');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new VarTag($name, $type, $variable, $description);
    }
}
