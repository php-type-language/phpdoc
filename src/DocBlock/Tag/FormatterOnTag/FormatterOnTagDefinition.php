<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@formatter:on` tag re-enables automatic code formatting from this
 * point on, pairing with an earlier `@formatter:off`.
 *
 * ```
 * "@formatter:on" [ <Description> ]
 * ```
 */
final class FormatterOnTagDefinition extends TagDefinition
{
    public const string NAME = 'formatter:on';

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

    public function create(string $name, TagPayload $result): FormatterOnTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new FormatterOnTag($name, $description);
    }
}
