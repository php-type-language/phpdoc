<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CopyrightTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@copyright` tag documents the copyright information of an element.
 *
 * ```
 * "@copyright" [ <Description> ]
 * ```
 */
final class CopyrightTagDefinition extends TagDefinition
{
    public const string NAME = 'copyright';

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

    public function create(string $name, TagPayload $result): CopyrightTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new CopyrightTag($name, $description);
    }
}
