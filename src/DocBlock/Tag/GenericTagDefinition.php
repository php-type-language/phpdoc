<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

final class GenericTagDefinition extends TagDefinition
{
    public const string NAME = '<Tag>';

    /**
     * @param TagPlacement $placement generic (unknown) tag has no dedicated
     *        definition, so its placement cannot be inferred from the tag
     *        itself and is decided by the caller
     */
    public function __construct(TagPlacement $placement = TagPlacement::DEFAULT)
    {
        parent::__construct(
            name: self::NAME,
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
