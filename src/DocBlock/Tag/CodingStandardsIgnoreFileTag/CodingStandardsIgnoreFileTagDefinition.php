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
 * The `@codingStandardsIgnoreFile` tag ignores the whole file for
 * coding-standard checks.
 *
 * ```
 * "@codingStandardsIgnoreFile" [ <Description> ]
 * ```
 */
final class CodingStandardsIgnoreFileTagDefinition extends TagDefinition
{
    public const string NAME = 'codingStandardsIgnoreFile';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Block,
        );
    }

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreFileTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new CodingStandardsIgnoreFileTag($name, $description);
    }
}
