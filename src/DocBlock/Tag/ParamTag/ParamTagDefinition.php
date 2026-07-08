<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamTag;

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
 * The `@param` tag documents a single argument of a function or method.
 *
 * The `@param` tag MAY be followed by a description that clarifies the
 * meaning of the argument.
 *
 * ```
 * "@param" <Type> <Variable> [ <Description> ]
 * ```
 */
final class ParamTagDefinition extends TagDefinition
{
    public const string NAME = 'param';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): ParamTag
    {
        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var non-empty-string $variable */
        $variable = $result->get('variable');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ParamTag($name, $type, $variable, $description);
    }
}
