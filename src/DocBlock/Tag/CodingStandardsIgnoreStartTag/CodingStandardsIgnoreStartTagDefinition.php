<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreStartTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@​codingStandardsIgnoreStart` tag starts a block of code excluded
 * from coding-standard checks, closed by a matching
 * `@​codingStandardsIgnoreEnd`.
 *
 * The `@codingstandardsignorestart` tag starts a block of code excluded
 * from coding-standard checks, closed by a matching
 * `@​codingStandardsIgnoreEnd`.
 *
 * ```
 * "@​codingStandardsIgnoreStart" [ <Description> ]
 * ```
 *
 * @generated from resources/model/phpcs.xml, edit the model instead
 */
final class CodingStandardsIgnoreStartTagDefinition extends TagDefinition
{
    /**
     * @return non-empty-string
     */
    public static function getCanonicalName(): string
    {
        return 'codingStandardsIgnoreStart';
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
                The `@codingStandardsIgnoreStart` tag starts a block of code excluded
                from coding-standard checks, closed by a matching
                `@codingStandardsIgnoreEnd`.

                The `@codingstandardsignorestart` tag starts a block of code excluded
                from coding-standard checks, closed by a matching
                `@codingStandardsIgnoreEnd`.
                TEXT,
        );
    }

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreStartTag
    {
        /** @var DescriptionInterface|null */
        $description = $result->find('description');

        return new CodingStandardsIgnoreStartTag(
            name: $name,
            description: $description,
        );
    }
}
