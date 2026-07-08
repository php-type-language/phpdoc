<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmOverrideMethodVisibilityTag;

use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\Spec;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPayload;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;

/**
 * The `@psalm-override-method-visibility` tag allows a subclass to
 * override an inherited method with a different visibility than the parent
 * declares.
 *
 * ```
 * "@psalm-override-method-visibility" [ <Description> ]
 * ```
 */
final class PsalmOverrideMethodVisibilityTagDefinition extends TagDefinition
{
    public const string NAME = 'psalm-override-method-visibility';

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

    public function create(string $name, TagPayload $result): PsalmOverrideMethodVisibilityTag
    {
        /** @var DescriptionInterface|null $description */
        $description = $result->find('description');

        return new PsalmOverrideMethodVisibilityTag($name, $description);
    }
}
