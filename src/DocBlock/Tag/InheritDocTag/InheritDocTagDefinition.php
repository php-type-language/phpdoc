<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritDocTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@inheritdoc`" tag reuses the documentation of the parent element.
 *
 * ```
 * "@inheritdoc" [ <Description> ]
 * ```
 */
final class InheritDocTagDefinition extends TagDefinition
{
    public const string NAME = 'inheritdoc';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: TagPlacement::Inline,
        );
    }

    public function create(string $name, TagPayload $result): InheritDocTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new InheritDocTag($name, $description);
    }
}
