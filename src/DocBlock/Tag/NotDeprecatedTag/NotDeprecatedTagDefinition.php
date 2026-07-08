<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@not-deprecated` tag marks an element as explicitly not deprecated,
 * overriding a `@deprecated` tag it would otherwise inherit from a parent.
 *
 * ```
 * "@not-deprecated" [ <Description> ]
 * ```
 */
final class NotDeprecatedTagDefinition extends TagDefinition
{
    public const string NAME = 'not-deprecated';

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

    public function create(string $name, TagPayload $result): NotDeprecatedTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new NotDeprecatedTag($name, $description);
    }
}
