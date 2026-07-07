<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\StaticTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The "`@static`" tag declares a method or property as static.
 *
 * ```
 * "@static" [ <Description> ]
 * ```
 */
final class StaticTagDefinition extends TagDefinition
{
    public const string NAME = 'static';

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

    public function create(string $name, TagPayload $result): StaticTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new StaticTag($name, $description);
    }
}
