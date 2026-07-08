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
 * The type-alias tag binds a name to a type expression, with an optional "="
 * between the two.
 *
 * ```
 * "@type" <Name> [ "=" ] <Type>
 * ```
 */
final class TypeAliasTagDefinition extends TagDefinition
{
    public const string NAME = 'type';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
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
        /** @var non-empty-string $alias */
        $alias = $result->get('alias');

        /** @var TypeReference $type */
        $type = $result->get('type');

        return new TypeAliasTag($name, $alias, $type);
    }
}
