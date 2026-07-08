<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVarTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-var` tag excludes the immediately following `@var`
 * annotation from type inference, falling back to the inferred type
 * instead of the declared one.
 *
 * ```
 * "@psalm-ignore-var" [ <Description> ]
 * ```
 */
final class PsalmIgnoreVarTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-ignore-var';

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

    public function create(string $name, TagPayload $result): PsalmIgnoreVarTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmIgnoreVarTag($name, $description);
    }
}
