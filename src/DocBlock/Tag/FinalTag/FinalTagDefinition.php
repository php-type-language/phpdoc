<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FinalTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@final`" tag declares that an element must not be overridden or
 * extended.
 *
 * ```
 * "@final" [ <Description> ]
 * ```
 */
final class FinalTagDefinition extends TagDefinition
{
    public const string NAME = 'final';

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

    public function create(string $name, TagPayload $result): FinalTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new FinalTag($name, $description);
    }
}
