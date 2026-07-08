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
 * The `@codingStandardsIgnoreLine` tag ignores a single line of code for
 * coding-standard checks.
 *
 * ```
 * "@codingStandardsIgnoreLine" [ <Description> ]
 * ```
 */
final class CodingStandardsIgnoreLineTagDefinition extends TagDefinition
{
    public const string NAME = 'codingStandardsIgnoreLine';

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

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreLineTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new CodingStandardsIgnoreLineTag($name, $description);
    }
}
