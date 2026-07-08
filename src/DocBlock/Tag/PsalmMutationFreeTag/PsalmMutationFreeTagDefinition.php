<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmMutationFreeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-mutation-free` tag declares that a method never mutates
 * any state, whether observable from outside the object or purely internal
 * to it.
 *
 * ```
 * "@psalm-mutation-free" [ <Description> ]
 * ```
 */
final class PsalmMutationFreeTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-mutation-free';

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

    public function create(string $name, TagPayload $result): PsalmMutationFreeTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmMutationFreeTag($name, $description);
    }
}
