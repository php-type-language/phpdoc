<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SubpackageTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@subpackage` tag categorizes elements into a logical subdivision below
 * their package.
 *
 * ```
 * "@subpackage" [ <Description> ]
 * ```
 */
final class SubpackageTagDefinition extends TagDefinition
{
    public const string NAME = 'subpackage';

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

    public function create(string $name, TagPayload $result): SubpackageTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SubpackageTag($name, $description);
    }
}
