<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamInvokedCallableTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@param-later-invoked-callable` tag marks a callable argument that is
 * invoked after the function or method returns.
 *
 * ```
 * "@param-later-invoked-callable" <Variable> [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class ParamLaterInvokedCallableTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'param-later-invoked-callable';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@param-later-invoked-callable` tag marks a callable argument that is
                invoked after the function or method returns.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ParamLaterInvokedCallableTag
    {
        /** @var non-empty-string */
        $variable = $result->get('variable');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ParamLaterInvokedCallableTag(
            name: $name,
            variable: $variable,
            description: $description,
        );
    }
}
