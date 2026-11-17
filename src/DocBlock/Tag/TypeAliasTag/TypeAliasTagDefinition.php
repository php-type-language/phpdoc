<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag;

use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * A tag that declares a local type alias: a name bound to a type expression.
 *
 * The type-alias tag binds a name to a type expression, with an optional "="
 * between the two.
 *
 * ```
 * "@type" <Name> [ "=" ] <Type>
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class TypeAliasTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'type';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'alias'),
                Spec::maybe(
                    Spec::literal('='),
                ),
                Spec::rule(TypeCombinator::NAME, 'type'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): TypeAliasTag
    {
        /** @var non-empty-string */
        $alias = $result->get('alias');

        /** @var TypeReference */
        $statement = $result->get('type');

        return new TypeAliasTag(
            name: $name,
            alias: $alias,
            statement: $statement,
        );
    }
}
