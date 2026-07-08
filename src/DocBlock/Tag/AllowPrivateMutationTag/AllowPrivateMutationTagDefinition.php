<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AllowPrivateMutationTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@allow-private-mutation` tag allows a private-scope mutation of
 * an otherwise immutable property.
 *
 * ```
 * "@allow-private-mutation" [ <Description> ]
 * ```
 */
final class AllowPrivateMutationTagDefinition extends TagDefinition
{
    public const string NAME = 'allow-private-mutation';

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

    public function create(string $name, TagPayload $result): AllowPrivateMutationTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new AllowPrivateMutationTag($name, $description);
    }
}
