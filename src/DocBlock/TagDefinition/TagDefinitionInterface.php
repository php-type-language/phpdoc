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
 *
 * @property-read non-empty-string $name Canonical tag name.
 * @property-read RuleInterface $spec Tag definition specification: shape of the tag body.
 * @property-read TagPlacement $placement Where the tag may appear: only inline, only as a block tag, or anywhere.
 */
interface TagDefinitionInterface extends \Stringable
{
    /**
     * Builds the tag from the values captured while matching {@see $spec}.
     *
     * @param non-empty-string $name the tag name, without the leading "@"
     */
    public function create(string $name, TagPayload $result): TagInterface;
}
