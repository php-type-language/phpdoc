<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariablePropertyTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-ignore-variable-property` tag suppresses "undefined
 * property" issues for properties accessed on the annotated variable.
 *
 * ```
 * "@psalm-ignore-variable-property" [ <Description> ]
 * ```
 */
final class PsalmIgnoreVariablePropertyTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-ignore-variable-property';

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

    public function create(string $name, TagPayload $result): PsalmIgnoreVariablePropertyTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmIgnoreVariablePropertyTag($name, $description);
    }
}
