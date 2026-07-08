<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\FormatterOffTag\FormatterOffTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag\FormatterOnTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The PhpStorm platform: the tag family understood by the PhpStorm IDE.
 */
final class PhpStormPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'PhpStorm';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            FormatterOffTagDefinition::NAME => new FormatterOffTagDefinition(),
            FormatterOnTagDefinition::NAME => new FormatterOnTagDefinition(),
        ];
    }
}
