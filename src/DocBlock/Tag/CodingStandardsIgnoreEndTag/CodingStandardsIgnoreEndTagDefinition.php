<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreEndTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@codingStandardsIgnoreEnd` tag ends a block of code started by
 * `@codingStandardsIgnoreStart`, resuming coding-standard checks from this
 * point on.
 *
 * ```
 * "@codingStandardsIgnoreEnd" [ <Description> ]
 * ```
 */
final class CodingStandardsIgnoreEndTagDefinition extends TagDefinition
{
    public const string NAME = 'codingStandardsIgnoreEnd';

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

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreEndTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new CodingStandardsIgnoreEndTag($name, $description);
    }
}
