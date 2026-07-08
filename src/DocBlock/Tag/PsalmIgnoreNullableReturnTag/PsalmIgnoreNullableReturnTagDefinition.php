<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreNullableReturnTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-nullable-return` tag ignores a possible `null`
 * value in the return type.
 *
 * ```
 * "@psalm-ignore-nullable-return" [ <Description> ]
 * ```
 */
final class PsalmIgnoreNullableReturnTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-ignore-nullable-return';

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

    public function create(string $name, TagPayload $result): PsalmIgnoreNullableReturnTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmIgnoreNullableReturnTag($name, $description);
    }
}
