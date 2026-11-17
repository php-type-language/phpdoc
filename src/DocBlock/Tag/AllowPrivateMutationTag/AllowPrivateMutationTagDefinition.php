<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AllowPrivateMutationTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@allow-private-mutation` tag allows a private-scope mutation of an
 * otherwise immutable property.
 *
 * ```
 * "@allow-private-mutation" [ <Description> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class AllowPrivateMutationTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'allow-private-mutation';
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

    public function create(string $name, TagPayload $result): AllowPrivateMutationTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new AllowPrivateMutationTag(
            name: $name,
            description: $description,
        );
    }
}
