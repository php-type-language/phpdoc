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
 * The `@psalm-check-type` tag reports an issue unless the inferred type of the
 * given variable is assignable to the expected type.
 *
 * ```
 * "@psalm-check-type" <Variable> "=" <Type>
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmCheckTypeTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-check-type';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(VariableCombinator::NAME, 'variable'),
                Spec::literal('='),
                Spec::rule(TypeCombinator::NAME, 'type'),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@psalm-check-type` tag reports an issue unless the inferred type of the
                given variable is assignable to the expected type.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmCheckTypeTag
    {
        /** @var non-empty-string */
        $variable = $result->get('variable');

        /** @var TypeReference */
        $statement = $result->get('type');

        return new PsalmCheckTypeTag(
            name: $name,
            variable: $variable,
            statement: $statement,
        );
    }
}
