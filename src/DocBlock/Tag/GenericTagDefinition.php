<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The definition of a tag known only by its name: a tag without a registered
 * definition. Its whole body is an optional description.
 *
 * ```
 * "@<name>" [ <Description> ]
 * ```
 */
final class GenericTagDefinition extends TagDefinition
{
    /**
     * @param non-empty-string $name the tag name, without the leading "@"
     * @param TagPlacement $placement generic (unknown) tag has no dedicated
     *        definition, so its placement cannot be inferred from the tag
     *        itself and is decided by the caller
     */
    public function __construct(string $name, TagPlacement $placement = TagPlacement::DEFAULT)
    {
        parent::__construct(
            name: $name,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            placement: $placement,
        );
    }

    public function create(string $name, TagPayload $result): Tag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new Tag(
            name: $name,
            description: $description,
        );
    }
}
