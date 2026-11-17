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
use TypeLang\PhpDoc\DocBlock\Tag\TodoTag\TodoTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\VersionTag\VersionTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;

/**
 * The phpDocumentor platform: the legacy tag family defined by phpDocumentor
 * that a modern, static-analysis-oriented docblock rarely uses.
 *
 * It contributes these older tags and the few combinators only they rely on.
 *
 * @phpstan-import-type CombinatorType from CombinatorInterface
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class PhpDocumentorPlatform extends Platform
{
    /**
     * @return iterable<non-empty-string, TagDefinitionInterface>
     */
    public function getTags(): iterable
    {
        return [
            AccessTagDefinition::getCanonicalName() => new AccessTagDefinition(),
            AuthorTagDefinition::getCanonicalName() => new AuthorTagDefinition(),
            CategoryTagDefinition::getCanonicalName() => new CategoryTagDefinition(),
            CopyrightTagDefinition::getCanonicalName() => new CopyrightTagDefinition(),
            ExampleTagDefinition::getCanonicalName() => new ExampleTagDefinition(),
            FilesourceTagDefinition::getCanonicalName() => new FilesourceTagDefinition(),
            GlobalTagDefinition::getCanonicalName() => new GlobalTagDefinition(),
            IgnoreTagDefinition::getCanonicalName() => new IgnoreTagDefinition(),
            InheritDocTagDefinition::getCanonicalName() => new InheritDocTagDefinition(),
            LicenseTagDefinition::getCanonicalName() => new LicenseTagDefinition(),
            NameTagDefinition::getCanonicalName() => new NameTagDefinition(),
            PackageTagDefinition::getCanonicalName() => new PackageTagDefinition(),
            SinceTagDefinition::getCanonicalName() => new SinceTagDefinition(),
            SourceTagDefinition::getCanonicalName() => new SourceTagDefinition(),
            StaticTagDefinition::getCanonicalName() => new StaticTagDefinition(),
            StaticVarTagDefinition::getCanonicalName() => new StaticVarTagDefinition(),
            SubpackageTagDefinition::getCanonicalName() => new SubpackageTagDefinition(),
            TodoTagDefinition::getCanonicalName() => new TodoTagDefinition(),
            UsedByTagDefinition::getCanonicalName() => new UsedByTagDefinition(),
            UsesTagDefinition::getCanonicalName() => new UsesTagDefinition(),
            VersionTagDefinition::getCanonicalName() => new VersionTagDefinition(),
        ];
    }

    /**
     * @return iterable<non-empty-string, CombinatorType>
     */
    public function getCombinators(): iterable
    {
        return [
            AuthorNameCombinator::NAME => new AuthorNameCombinator(),
            EmailCombinator::NAME => new EmailCombinator(),
            VisibilityCombinator::NAME => new VisibilityCombinator(),
        ];
    }
}
