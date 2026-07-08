<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSpecializeTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-taint-specialize` tag tracks tainted data separately per
 * call site, rather than merging taint information from all callers
 * together.
 *
 * ```
 * "@psalm-taint-specialize" [ <Description> ]
 * ```
 */
final class PsalmTaintSpecializeTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-taint-specialize';

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

    public function create(string $name, TagPayload $result): PsalmTaintSpecializeTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmTaintSpecializeTag($name, $description);
    }
}
