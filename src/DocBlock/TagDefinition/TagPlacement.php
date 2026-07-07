<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\TagDefinition;

/**
 * Describes where a tag is allowed to appear within a DocBlock.
 */
enum TagPlacement
{
    /**
     * The tag is only valid inline, inside a description as a "{@tag}" sequence.
     */
    case Inline;

    /**
     * The tag is only valid as a block tag occupying its own line.
     */
    case Block;

    /**
     * The tag may be used both inline and as a block tag.
     */
    case Any;

    /**
     * Placement assumed when a definition does not specify one.
     */
    public const self DEFAULT = self::Any;
}
