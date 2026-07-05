<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\IgnoreTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@ignore`" tag tells documentation tooling to skip the element it is
 * attached to.
 *
 * ```
 * "@ignore" [ <Description> ]
 * ```
 */
final class IgnoreTagDefinition extends TagDefinition
{
    public const string NAME = 'ignore';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            isInline: false,
        );
    }

    public function create(string $name, TagPayload $result): IgnoreTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new IgnoreTag($name, $description);
    }
}
