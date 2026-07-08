<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\FormatterOffTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@formatter:off` tag disables automatic code formatting from this
 * point on, until a matching `@formatter:on` is found further down the
 * file.
 *
 * ```
 * "@formatter:off" [ <Description> ]
 * ```
 */
final class FormatterOffTagDefinition extends TagDefinition
{
    public const string NAME = 'formatter:off';

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

    public function create(string $name, TagPayload $result): FormatterOffTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new FormatterOffTag($name, $description);
    }
}
