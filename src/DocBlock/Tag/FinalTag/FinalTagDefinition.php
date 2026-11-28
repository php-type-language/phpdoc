<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FinalTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@final` tag declares that an element must not be overridden or extended.
 *
 * ```
 * "@final" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class FinalTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'final';
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
                The `@final` tag declares that an element must not be overridden or extended.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): FinalTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new FinalTag(
            name: $name,
            description: $description,
        );
    }
}
