<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmFlowTag;

use TypeLang\PhpDoc\DocBlock\Combinator\FlowTypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\FlowType;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-flow` tag describes how tainted data flows through a function,
 * optionally naming the parameter the flow applies to.
 *
 * ```
 * "@psalm-flow" <FlowType> [ <Variable> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmFlowTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-flow';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(FlowTypeCombinator::NAME, 'flow'),
                Spec::maybe(
                    Spec::rule(VariableCombinator::NAME, 'variable'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@psalm-flow` tag describes how tainted data flows through a function,
                optionally naming the parameter the flow applies to.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmFlowTag
    {
        /** @var FlowType */
        $flow = $result->get('flow');

        /** @var non-empty-string|null */
        $variable = $result->find('variable');

        return new PsalmFlowTag(
            name: $name,
            flow: $flow,
            variable: $variable,
        );
    }
}
