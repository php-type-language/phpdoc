<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NoNamedArgumentsTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@no-named-arguments` tag indicates that the argument names may change
 * and must not be relied upon by callers.
 *
 * ```
 * "@no-named-arguments" [ <Description> ]
 * ```
 */
final class NoNamedArgumentsTagDefinition extends TagDefinition
{
    public const string NAME = 'no-named-arguments';

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

    public function create(string $name, TagPayload $result): NoNamedArgumentsTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new NoNamedArgumentsTag($name, $description);
    }
}
