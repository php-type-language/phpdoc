<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@pure` tag declares a function or method as pure, meaning it is
 * free of side effects: calling it repeatedly with the same arguments
 * always produces the same result, without observably mutating any state.
 *
 * ```
 * "@pure" [ <Description> ]
 * ```
 */
final class PureTagDefinition extends TagDefinition
{
    public const string NAME = 'pure';

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

    public function create(string $name, TagPayload $result): PureTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PureTag($name, $description);
    }
}
