<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * A platform contributes a named set of tags, aliases and combinators to the
 * parser.
 *
 * The {@see StandardPlatform} is always loaded first; every additional platform
 * extends it, overriding an entry when it reuses the same name.
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
interface PlatformInterface
{
    /**
     * Human-readable platform name.
     *
     * @var non-empty-string
     */
    public string $name {
        get;
    }

    /**
     * Tag definitions keyed by their canonical (lower-case) name.
     *
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get;
    }

    /**
     * Alias-to-canonical name pairs, both lower-case.
     *
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get;
    }

    /**
     * Grammar combinators keyed by their name.
     *
     * @var iterable<non-empty-string, CombinatorType>
     */
    public iterable $combinators {
        get;
    }
}
