<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@immutable` tag declares a class as immutable, meaning none of its state
 * can change after construction.
 *
 * ```
 * "@immutable" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class ImmutableTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'immutable';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@immutable` tag declares a class as immutable, meaning none of its state
                can change after construction.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): ImmutableTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new ImmutableTag(
            name: $name,
            description: $description,
        );
    }
}
