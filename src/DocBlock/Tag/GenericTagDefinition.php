<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;

final class GenericTagDefinition extends TagDefinition
{
    public const string NAME = '<Tag>';

    /**
     * @param bool $isInline Generic (unknown) tag has no dedicated definition,
     *        so whether it is allowed inline cannot be inferred from the tag
     *        itself and is decided by the caller. It is not inline by default:
     *        an unrecognized "{@tag}" in running text stays raw text.
     */
    public function __construct(bool $isInline = false)
    {
        parent::__construct(
            name: self::NAME,
            spec: Spec::maybe(
                Spec::rule(DescriptionCombinator::NAME, 'description'),
            ),
            isInline: $isInline,
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
