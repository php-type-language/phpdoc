<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@immutable`" tag declares a class as immutable, meaning none of its
 * state can change after construction.
 *
 * ```
 * "@immutable" [ <Description> ]
 * ```
 */
final class ImmutableTagDefinition extends TagDefinition
{
    public const string NAME = 'immutable';

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

    public function create(string $name, TagPayload $result): ImmutableTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ImmutableTag($name, $description);
    }
}
