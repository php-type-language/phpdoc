<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Parser\Grammar\Rule\RuleInterface;

/**
 * Declares a single tag.
 *
 * The shape of its body (a {@see RuleInterface}) and how to build a {@see TagInterface}
 * from the parsed pieces.
 */
interface TagDefinitionInterface extends \Stringable
{
    /**
     * Canonical tag name
     *
     * @var non-empty-string
     */
    public string $name {
        get;
    }

    /**
     * Tag definition specification: shape of the tag body.
     */
    public RuleInterface $spec {
        get;
    }

    /**
     * Whether the tag may be used inline, that is inside a description as
     * a "{@tag}" sequence.
     *
     * A block-only tag (such as "@param") is never lifted out of a description:
     * a "{@param}" written in running text stays raw text instead of being
     * parsed as an inline tag.
     */
    public bool $isInline {
        get;
    }

    /**
     * Builds the tag from the values captured while matching {@see $spec}.
     *
     * @param non-empty-string $name the tag name, without the leading "@"
     */
    public function create(string $name, TagPayload $result): TagInterface;
}
