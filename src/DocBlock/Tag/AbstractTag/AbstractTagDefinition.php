<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AbstractTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@abstract` tag declares an element as abstract.
 *
 * ```
 * "@abstract" [ <Description> ]
 * ```
 *
 * @generated from resources/model/standard.xml, edit the model instead
 */
final class AbstractTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'abstract';
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
                The `@abstract` tag declares an element as abstract.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): AbstractTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new AbstractTag(
            name: $name,
            description: $description,
        );
    }
}
