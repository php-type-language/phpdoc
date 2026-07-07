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
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [];
    }

    /**
     * @var iterable<non-empty-lowercase-string, non-empty-lowercase-string>
     */
    public iterable $aliases {
        get => [];
    }

    /**
     * @var iterable<non-empty-string, CombinatorType>
     */
    public iterable $combinators {
        get => [];
    }
}
