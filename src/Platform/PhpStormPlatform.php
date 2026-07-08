<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\ExpectedExceptionTag\ExpectedExceptionTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FormatterOffTag\FormatterOffTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FormatterOnTag\FormatterOnTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\LanguageTag\LanguageTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NoinspectionTag\NoinspectionTagDefinition;
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
     * @var iterable<non-empty-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            ExpectedExceptionTagDefinition::NAME => new ExpectedExceptionTagDefinition(),
            FormatterOffTagDefinition::NAME => new FormatterOffTagDefinition(),
            FormatterOnTagDefinition::NAME => new FormatterOnTagDefinition(),
            LanguageTagDefinition::NAME => new LanguageTagDefinition(),
            NoinspectionTagDefinition::NAME => new NoinspectionTagDefinition(),
        ];
    }
}
