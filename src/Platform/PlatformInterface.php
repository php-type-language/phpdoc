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
     * @return iterable<non-empty-string, CombinatorType>
     */
    public function getCombinators(): iterable;
}
