<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreFalsableReturnTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-falsable-return` tag ignores a possible `false`
 * value in the return type.
 *
 * ```
 * "@psalm-ignore-falsable-return" [ <Description> ]
 * ```
 */
final class PsalmIgnoreFalsableReturnTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-ignore-falsable-return';

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

    public function create(string $name, TagPayload $result): PsalmIgnoreFalsableReturnTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmIgnoreFalsableReturnTag($name, $description);
    }
}
