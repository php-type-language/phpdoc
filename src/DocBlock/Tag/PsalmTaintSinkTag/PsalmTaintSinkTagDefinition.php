<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSinkTag;

use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-taint-sink` tag marks the given variable as a sink where
 * tainted data of the named taint type must never arrive.
 *
 * ```
 * "@psalm-taint-sink" <Name> <Variable>
 * ```
 */
final class PsalmTaintSinkTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-taint-sink';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'taint'),
                Spec::rule(VariableCombinator::NAME, 'variable'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): PsalmTaintSinkTag
    {
        /** @var non-empty-string $taint */
        $taint = $result->get('taint');

        /** @var non-empty-string $variable */
        $variable = $result->get('variable');

        return new PsalmTaintSinkTag($name, $taint, $variable);
    }
}
