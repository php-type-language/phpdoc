<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SuppressTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

/**
 * The "`@suppress`" tag silences the diagnostics that would otherwise be
 * reported for an element.
 *
 * ```
 * "@suppress" [ <Description> ]
 * ```
 */
final class SuppressTagDefinition extends TagDefinition
{
    public const string NAME = 'suppress';

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

    public function create(string $name, TagPayload $result): SuppressTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new SuppressTag($name, $description);
    }
}
