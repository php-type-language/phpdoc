<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreEndTag\CodingStandardsIgnoreEndTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreFileTag\CodingStandardsIgnoreFileTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreLineTag\CodingStandardsIgnoreLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreStartTag\CodingStandardsIgnoreStartTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsTag\CodingStandardsTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PhpcsSuppressTag\PhpcsSuppressTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The PHP CodeSniffer platform: the `@​phpcs:*` tag family understood by PHP
 * CodeSniffer.
 *
 * @generated from resources/model/phpcs.xml, edit the model instead
 */
final class PhpCodeSnifferPlatform extends Platform
{
    /**
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [
            CodingStandardsTagDefinition::getCanonicalName() => new CodingStandardsTagDefinition(),
            CodingStandardsIgnoreEndTagDefinition::getCanonicalName() => new CodingStandardsIgnoreEndTagDefinition(),
            CodingStandardsIgnoreFileTagDefinition::getCanonicalName() => new CodingStandardsIgnoreFileTagDefinition(),
            CodingStandardsIgnoreLineTagDefinition::getCanonicalName() => new CodingStandardsIgnoreLineTagDefinition(),
            CodingStandardsIgnoreStartTagDefinition::getCanonicalName() => new CodingStandardsIgnoreStartTagDefinition(),
            PhpcsSuppressTagDefinition::getCanonicalName() => new PhpcsSuppressTagDefinition(),
        ];
    }
}
