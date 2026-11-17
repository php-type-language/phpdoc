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
 * variable, optionally naming the variable it applies to.
 *
 * ```
 * "@var" <Type> [ <Variable> ] [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class VarTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'var';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
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
        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var non-empty-string|null */
        $variable = $result->find('variable');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new VarTag(
            name: $name,
            statement: $statement,
            variable: $variable,
            description: $description,
        );
    }
}
