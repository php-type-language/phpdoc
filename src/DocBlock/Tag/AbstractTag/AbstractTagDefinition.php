<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AbstractTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@abstract`" tag declares an element as abstract.
 *
 * ```
 * "@abstract" [ <Description> ]
 * ```
 */
final class AbstractTagDefinition extends TagDefinition
{
    public const string NAME = 'abstract';

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

    public function create(string $name, TagPayload $result): AbstractTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new AbstractTag($name, $description);
    }
}
