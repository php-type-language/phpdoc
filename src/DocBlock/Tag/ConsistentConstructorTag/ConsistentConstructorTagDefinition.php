<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ConsistentConstructorTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@consistent-constructor` tag requires that all subclasses declare
 * a constructor compatible with the parent's.
 *
 * ```
 * "@consistent-constructor" [ <Description> ]
 * ```
 */
final class ConsistentConstructorTagDefinition extends TagDefinition
{
    public const string NAME = 'consistent-constructor';

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

    public function create(string $name, TagPayload $result): ConsistentConstructorTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new ConsistentConstructorTag($name, $description);
    }
}
