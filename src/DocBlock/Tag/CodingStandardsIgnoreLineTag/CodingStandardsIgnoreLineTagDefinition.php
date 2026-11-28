<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreLineTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​codingStandardsIgnoreLine` tag ignores a single line of code for
 * coding-standard checks.
 *
 * ```
 * "@​codingStandardsIgnoreLine" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpcs.xml, edit the model instead
 */
final class CodingStandardsIgnoreLineTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'codingStandardsIgnoreLine';
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
                The `@codingStandardsIgnoreLine` tag ignores a single line of code for
                coding-standard checks.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreLineTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new CodingStandardsIgnoreLineTag(
            name: $name,
            description: $description,
        );
    }
}
