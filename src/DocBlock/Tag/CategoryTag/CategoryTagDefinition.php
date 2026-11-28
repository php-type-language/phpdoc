<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CategoryTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@category` tag organizes groups of packages together.
 *
 * ```
 * "@category" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class CategoryTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-lowercase-string
     */
    public static function getCanonicalName(): string
    {
        return 'category';
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
                The `@category` tag organizes groups of packages together.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): CategoryTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new CategoryTag(
            name: $name,
            description: $description,
        );
    }
}
