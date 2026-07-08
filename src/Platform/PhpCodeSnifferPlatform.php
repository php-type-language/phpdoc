<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreEndTag\CodingStandardsIgnoreEndTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreFileTag\CodingStandardsIgnoreFileTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreLineTag\CodingStandardsIgnoreLineTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreStartTag\CodingStandardsIgnoreStartTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;

/**
 * The PHP CodeSniffer platform: the `@phpcs:*` tag family understood by PHP
 * CodeSniffer.
 */
final class PhpCodeSnifferPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'PHP CodeSniffer';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            CodingStandardsIgnoreStartTagDefinition::NAME => new CodingStandardsIgnoreStartTagDefinition(),
            CodingStandardsIgnoreEndTagDefinition::NAME => new CodingStandardsIgnoreEndTagDefinition(),
            CodingStandardsIgnoreLineTagDefinition::NAME => new CodingStandardsIgnoreLineTagDefinition(),
            CodingStandardsIgnoreFileTagDefinition::NAME => new CodingStandardsIgnoreFileTagDefinition(),
        ];
    }
}
