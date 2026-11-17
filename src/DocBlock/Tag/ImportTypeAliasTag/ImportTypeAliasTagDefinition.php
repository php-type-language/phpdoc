<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ImportTypeAliasTag;

use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * A tag that imports a type alias declared elsewhere, optionally rebinding it
 * to a local name.
 *
 * The import-type tag brings a type alias declared elsewhere into the current
 * scope, optionally rebinding it to a local name after "as".
 *
 * ```
 * "@import-type" <Name> "from" <Type> [ "as" <Name> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class ImportTypeAliasTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'import-type';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'alias'),
                Spec::literal('from'),
                Spec::rule(TypeCombinator::NAME, 'type'),
                Spec::maybe(
                    Spec::sequence(
                        Spec::literal('as'),
                        Spec::rule(NameCombinator::NAME, 'as'),
                    ),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): ImportTypeAliasTag
    {
        /** @var non-empty-string */
        $alias = $result->get('alias');

        /** @var TypeReference */
        $statement = $result->get('type');

        /** @var non-empty-string|null */
        $as = $result->find('as');

        return new ImportTypeAliasTag(
            name: $name,
            alias: $alias,
            statement: $statement,
            as: $as,
        );
    }
}
