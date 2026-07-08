<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LanguageTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@language`" tag injects a foreign-language grammar — such as
 * SQL, HTML or a regular expression — into a string literal, so an IDE
 * can apply the right highlighting and completion inside it.
 *
 * ```
 * "@language" <Name> [ <Description> ]
 * ```
 */
final class LanguageTagDefinition extends TagDefinition
{
    public const string NAME = 'language';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'identifier'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): LanguageTag
    {
        /** @var non-empty-string $identifier */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new LanguageTag($name, $identifier, $description);
    }
}
