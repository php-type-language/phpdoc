<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@codingStandards` tag names a coding standard for reference.
 *
 * ```
 * "@codingStandards" <Name> [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpcs.xml, edit the model instead
 */
final class CodingStandardsTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'codingStandards';
    }

    public function __construct()
    {
        parent::__construct(
            name: self::getCanonicalName(),
            spec: Spec::sequence(
                Spec::rule(NameCombinator::NAME, 'identifier'),
                Spec::maybe(
                    Spec::rule(DescriptionCombinator::NAME, 'description'),
                ),
            ),
            placement: TagPlacement::Block,
            description: <<<'TEXT'
                The `@codingStandards` tag names a coding standard for reference.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): CodingStandardsTag
    {
        /** @var non-empty-string */
        $identifier = $result->get('identifier');

        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new CodingStandardsTag(
            name: $name,
            identifier: $identifier,
            description: $description,
        );
    }
}
