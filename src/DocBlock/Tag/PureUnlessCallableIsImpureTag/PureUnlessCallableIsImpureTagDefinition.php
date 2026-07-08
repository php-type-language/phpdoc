<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@pure-unless-callable-is-impure` tag declares a function pure unless a
 * callable it receives is itself impure.
 *
 * ```
 * "@pure-unless-callable-is-impure" [ <Description> ]
 * ```
 */
final class PureUnlessCallableIsImpureTagDefinition extends TagDefinition
{
    public const string NAME = 'pure-unless-callable-is-impure';

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

    public function create(string $name, TagPayload $result): PureUnlessCallableIsImpureTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PureUnlessCallableIsImpureTag($name, $description);
    }
}
