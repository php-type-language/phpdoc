<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSideEffectFreeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@phan-side-effect-free` tag declares a function or method as free
 * of side effects.
 *
 * ```
 * "@phan-side-effect-free" [ <Description> ]
 * ```
 */
final class PhanSideEffectFreeTagDefinition extends TagDefinition
{
    public const string NAME = 'phan-side-effect-free';

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

    public function create(string $name, TagPayload $result): PhanSideEffectFreeTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PhanSideEffectFreeTag($name, $description);
    }
}
