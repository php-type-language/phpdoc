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
 * The `@language` tag injects a foreign-language grammar, such as SQL,
 * HTML or a regular expression, into a string literal, so an IDE can
 * apply the right highlighting and completion inside it.
 *
 * ```
 * "@language" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpstorm.xml, edit the model instead
 */
final class LanguageTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'language';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
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
        /** @var non-empty-string */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new LanguageTag(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
    }
}
