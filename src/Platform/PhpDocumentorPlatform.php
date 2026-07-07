<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Platform;

use TypeLang\PhpDoc\DocBlock\Combinator\AuthorNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\EmailCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VisibilityCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\AccessTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AuthorTag\AuthorTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CategoryTag\CategoryTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\CopyrightTag\CopyrightTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\ExampleTag\ExampleTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FilesourceTag\FilesourceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\GlobalTag\GlobalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\IgnoreTag\IgnoreTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InheritDocTag\InheritDocTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\LicenseTag\LicenseTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NameTag\NameTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\PackageTag\PackageTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SinceTag\SinceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SourceTag\SourceTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\StaticTag\StaticTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag\StaticVarTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\SubpackageTag\SubpackageTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * The phpDocumentor platform: the legacy tag family defined by phpDocumentor
 * that a modern, static-analysis-oriented docblock rarely uses.
 *
 * It extends the {@see StandardPlatform} with these older tags and the few
 * combinators only they rely on.
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 */
final class PhpDocumentorPlatform extends Platform
{
    /**
     * @var non-empty-string
     */
    public const string NAME = 'phpDocumentor';

    public private(set) string $name = self::NAME;

    /**
     * @var iterable<non-empty-lowercase-string, TagDefinitionInterface>
     */
    public iterable $tags {
        get => [
            AccessTagDefinition::NAME => new AccessTagDefinition(),
            AuthorTagDefinition::NAME => new AuthorTagDefinition(),
            CategoryTagDefinition::NAME => new CategoryTagDefinition(),
            CopyrightTagDefinition::NAME => new CopyrightTagDefinition(),
            ExampleTagDefinition::NAME => new ExampleTagDefinition(),
            FilesourceTagDefinition::NAME => new FilesourceTagDefinition(),
            GlobalTagDefinition::NAME => new GlobalTagDefinition(),
            IgnoreTagDefinition::NAME => new IgnoreTagDefinition(),
            InheritDocTagDefinition::NAME => new InheritDocTagDefinition(),
            LicenseTagDefinition::NAME => new LicenseTagDefinition(),
            NameTagDefinition::NAME => new NameTagDefinition(),
            PackageTagDefinition::NAME => new PackageTagDefinition(),
            SinceTagDefinition::NAME => new SinceTagDefinition(),
            SourceTagDefinition::NAME => new SourceTagDefinition(),
            StaticTagDefinition::NAME => new StaticTagDefinition(),
            StaticVarTagDefinition::NAME => new StaticVarTagDefinition(),
            SubpackageTagDefinition::NAME => new SubpackageTagDefinition(),
            UsedByTagDefinition::NAME => new UsedByTagDefinition(),
            UsesTagDefinition::NAME => new UsesTagDefinition(),
        ];
    }

    /**
     * @var iterable<non-empty-string, CombinatorType>
     */
    public iterable $combinators {
        get => [
            VisibilityCombinator::NAME => new VisibilityCombinator(),
            AuthorNameCombinator::NAME => new AuthorNameCombinator(),
            EmailCombinator::NAME => new EmailCombinator(),
        ];
    }
}
