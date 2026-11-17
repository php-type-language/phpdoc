<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * A platform contributes a named set of tags, aliases and combinators to the
 * parser.
 *
 * A platform is self-contained: its aliases point to its own tags, and its tags
 * reference the combinators of the parser itself (`Type`, `Variable`, `Name`,
 * ...) or its own ones. A later platform overrides an entry of an earlier one
 * when it reuses the same name.
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
interface PlatformInterface
{
    /**
     * Tag definitions keyed by their canonical (lower-case) name.
     *
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable;

    /**
     * Alias-to-canonical name pairs, both lower-case.
     *
     * @return iterable<non-empty-string, non-empty-string>
     */
    public function getAliases(): iterable;

    /**
     * Grammar combinators keyed by their name.
     *
     * The name is the contract of the combinator: a combinator with the name
     * of an existing one replaces it for the tags of all platforms.
     *
     * @return iterable<non-empty-string, CombinatorType>
     */
    public function getCombinators(): iterable;
}
