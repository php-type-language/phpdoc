<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ConsistentConstructorTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@consistent-constructor` tag requires that all subclasses declare a
 * constructor compatible with the parent's.
 *
 * ```
 * "@consistent-constructor" [ <Description> ]
 * ```
 *
 * @generated from resources/model/shared.xml, edit the model instead
 */
final class ConsistentConstructorTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'consistent-constructor';
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

    public function create(string $name, TagPayload $result): ConsistentConstructorTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ConsistentConstructorTag(
            name: $name,
            description: $description,
        );
    }
}
