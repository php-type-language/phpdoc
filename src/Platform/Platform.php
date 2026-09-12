<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
abstract class Platform implements PlatformInterface
{
    /**
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [];
    }

    /**
     * @return iterable<non-empty-string, non-empty-string>
     */
    public function getAliases(): iterable
    {
        return [];
    }

    /**
     * @return iterable<non-empty-string, CombinatorType>
     */
    public function getCombinators(): iterable
    {
        return [];
    }
}
