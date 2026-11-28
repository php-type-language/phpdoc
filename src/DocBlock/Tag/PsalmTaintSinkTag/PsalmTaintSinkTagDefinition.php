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
 * The `@psalm-taint-sink` tag marks the given variable as a sink where tainted
 * data of the named taint type must never arrive.
 *
 * ```
 * "@psalm-taint-sink" <Name> <Variable>
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmTaintSinkTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-taint-sink';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'taint'),
                Spec::rule(VariableCombinator::NAME, 'variable'),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@psalm-taint-sink` tag marks the given variable as a sink where tainted
                data of the named taint type must never arrive.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmTaintSinkTag
    {
        /** @var non-empty-string */
        $taint = $result->get('taint');

        /** @var non-empty-string */
        $variable = $result->get('variable');

        return new PsalmTaintSinkTag(
            name: $name,
            taint: $taint,
            variable: $variable,
        );
    }
}
