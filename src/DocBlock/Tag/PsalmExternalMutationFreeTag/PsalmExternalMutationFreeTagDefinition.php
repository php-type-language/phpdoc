<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmExternalMutationFreeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-external-mutation-free` tag declares that a method never
 * mutates state observable from outside the object.
 *
 * ```
 * "@psalm-external-mutation-free" [ <Description> ]
 * ```
 */
final class PsalmExternalMutationFreeTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-external-mutation-free';

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

    public function create(string $name, TagPayload $result): PsalmExternalMutationFreeTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmExternalMutationFreeTag($name, $description);
    }
}
