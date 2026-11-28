<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritDocTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@inheritDoc` tag reuses the documentation of the parent element.
 *
 * ```
 * "@inheritDoc" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class InheritDocTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'inheritDoc';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Inline,
            description: <<<'TEXT'
                The `@inheritDoc` tag reuses the documentation of the parent element.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): InheritDocTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new InheritDocTag(
            name: $name,
            description: $description,
        );
    }
}
