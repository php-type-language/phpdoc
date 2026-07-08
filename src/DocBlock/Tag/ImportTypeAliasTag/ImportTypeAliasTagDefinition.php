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
 * The import-type tag brings a type alias declared elsewhere into the current
 * scope, optionally rebinding it to a local name after "as".
 *
 * ```
 * "@import-type" <Name> "from" <Type> [ "as" <Name> ]
 * ```
 */
final class ImportTypeAliasTagDefinition extends TagDefinition
{
    public const string NAME = 'import-type';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
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
        /** @var non-empty-string $alias */
        $alias = $result->get('alias');

        /** @var TypeReference $type */
        $type = $result->get('type');

        /** @var non-empty-string|null $as */
        $as = $result->find('as');

        return new ImportTypeAliasTag($name, $alias, $type, $as);
    }
}
