<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\OverrideTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@override` tag marks a method as overriding an inherited definition.
 *
 * ```
 * "@override" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class OverrideTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'override';
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
                The `@override` tag marks a method as overriding an inherited definition.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): OverrideTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new OverrideTag(
            name: $name,
            description: $description,
        );
    }
}
