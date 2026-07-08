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
 * The `@codingstandardsignorestart` tag starts a block of code excluded
 * from coding-standard checks, closed by a matching
 * `@codingStandardsIgnoreEnd`.
 *
 * ```
 * "@codingStandardsIgnoreStart" [ <Description> ]
 * ```
 */
final class CodingStandardsIgnoreStartTagDefinition extends TagDefinition
{
    public const string NAME = 'codingStandardsIgnoreStart';

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

    public function create(string $name, TagPayload $result): CodingStandardsIgnoreStartTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new CodingStandardsIgnoreStartTag($name, $description);
    }
}
