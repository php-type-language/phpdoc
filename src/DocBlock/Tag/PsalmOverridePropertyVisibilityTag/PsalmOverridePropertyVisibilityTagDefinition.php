<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmOverridePropertyVisibilityTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-override-property-visibility` tag allows a subclass to
 * override an inherited property with a different visibility than the
 * parent declares.
 *
 * ```
 * "@psalm-override-property-visibility" [ <Description> ]
 * ```
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmOverridePropertyVisibilityTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'psalm-override-property-visibility';
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
                The `@psalm-override-property-visibility` tag allows a subclass to
                override an inherited property with a different visibility than the
                parent declares.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): PsalmOverridePropertyVisibilityTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new PsalmOverridePropertyVisibilityTag(
            name: $name,
            description: $description,
        );
    }
}
