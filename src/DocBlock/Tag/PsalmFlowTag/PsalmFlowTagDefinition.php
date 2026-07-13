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
 */
final class PsalmFlowTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-flow';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(FlowTypeCombinator::NAME, 'flow'),
                Spec::maybe(
                    Spec::rule(VariableCombinator::NAME, 'variable'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): PsalmFlowTag
    {
        /** @var FlowType $flow */
        $flow = $result->get('flow');

        /** @var non-empty-string|null $variable */
        $variable = $result->find('variable');

        return new PsalmFlowTag($name, $flow, $variable);
    }
}
