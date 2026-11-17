<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@not-deprecated` tag marks an element as explicitly not deprecated,
 * overriding a `@​deprecated` tag it would otherwise inherit from a parent.
 *
 * ```
 * "@not-deprecated" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class NotDeprecatedTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'not-deprecated';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): NotDeprecatedTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new NotDeprecatedTag(
            name: $name,
            description: $description,
        );
    }
}
