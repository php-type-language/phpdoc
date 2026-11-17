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
 *
 * @generated from resources/model/phpstorm.xml, edit the model instead
 */
final class PhpStormPlatform extends Platform
{
    /**
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [
            ExpectedExceptionTagDefinition::getCanonicalName() => new ExpectedExceptionTagDefinition(),
            FormatterOffTagDefinition::getCanonicalName() => new FormatterOffTagDefinition(),
            FormatterOnTagDefinition::getCanonicalName() => new FormatterOnTagDefinition(),
            LanguageTagDefinition::getCanonicalName() => new LanguageTagDefinition(),
            NoinspectionTagDefinition::getCanonicalName() => new NoinspectionTagDefinition(),
        ];
    }
}
