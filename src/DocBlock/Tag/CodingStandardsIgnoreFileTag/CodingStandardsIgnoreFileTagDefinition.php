<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreFileTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​codingStandardsIgnoreFile` tag ignores the whole file for
 * coding-standard checks.
 *
 * ```
 * "@​codingStandardsIgnoreFile" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpcs.xml, edit the model instead
 */
final class CodingStandardsIgnoreFileTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'codingStandardsIgnoreFile';
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

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreFileTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new CodingStandardsIgnoreFileTag(
            name: $name,
            description: $description,
        );
    }
}
