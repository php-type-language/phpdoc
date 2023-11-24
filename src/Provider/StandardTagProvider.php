<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Provider;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Tag\ApiTag;
use TypeLang\PhpDocParser\DocBlock\Tag\AuthorTag;
use TypeLang\PhpDocParser\DocBlock\Tag\CategoryTag;
use TypeLang\PhpDocParser\DocBlock\Tag\CopyrightTag;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromNameTypeAndDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromTagAndDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\DeprecatedTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ExampleTag;
use TypeLang\PhpDocParser\DocBlock\Tag\FilesourceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\FinalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\GlobalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\IgnoreTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ImmutableTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InheritDocTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InternalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\LicenseTag;
use TypeLang\PhpDocParser\DocBlock\Tag\LinkTag;
use TypeLang\PhpDocParser\DocBlock\Tag\MethodTag;
use TypeLang\PhpDocParser\DocBlock\Tag\NoNamedArgumentsTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PackageTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ParamTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyReadTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyWriteTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ReturnTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SeeTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SinceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SourceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SubPackageTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ThrowsTag;
use TypeLang\PhpDocParser\DocBlock\Tag\TodoTag;
use TypeLang\PhpDocParser\DocBlock\Tag\UsedByTag;
use TypeLang\PhpDocParser\DocBlock\Tag\UsesTag;
use TypeLang\PhpDocParser\DocBlock\Tag\VarTag;
use TypeLang\PhpDocParser\DocBlock\Tag\VersionTag;
use TypeLang\PhpDocParser\DocBlock\TagFactory\CreatableFromDescriptionTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactory\CreatableFromNameTypeAndDescriptionTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactory\CreatableFromTagAndDescriptionTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactory\LinkTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactoryInterface;

final class StandardTagProvider
{
    /**
     * @var list<non-empty-lowercase-string>
     */
    private const COMMON_PREFIXES = [
        'psalm',
        'phpstan',
        'phan'
    ];

    /**
     * @var array<non-empty-lowercase-string, class-string<CreatableFromDescriptionInterface>>
     */
    private const COMMON_TAGS = [
        'api' => ApiTag::class,
        'author' => AuthorTag::class,
        'category' => CategoryTag::class,
        'copyright' => CopyrightTag::class,
        'deprecated' => DeprecatedTag::class,
        'example' => ExampleTag::class,
        'filesource' => FilesourceTag::class,
        'final' => FinalTag::class,
        'ignore' => IgnoreTag::class,
        'immutable' => ImmutableTag::class,
        'inheritdoc' => InheritDocTag::class,
        'internal' => InternalTag::class,
        'license' => LicenseTag::class,
        'method' => MethodTag::class,
        'no-named-arguments' => NoNamedArgumentsTag::class,
        'package' => PackageTag::class,
        'since' => SinceTag::class,
        'source' => SourceTag::class,
        'subpackage' => SubPackageTag::class,
        'todo' => TodoTag::class,
        'used-by' => UsedByTag::class,
        'usedby' => UsedByTag::class,
        'uses' => UsesTag::class,
        'version' => VersionTag::class,
    ];

    /**
     * @var array<non-empty-lowercase-string, class-string<CreatableFromTagAndDescriptionInterface>>
     */
    private const TYPED_TAGS = [
        'var' => VarTag::class,
        'return' => ReturnTag::class,
        'returns' => ReturnTag::class,
        'throw' => ThrowsTag::class,
        'throws' => ThrowsTag::class,
    ];

    /**
     * @var array<non-empty-lowercase-string, class-string<CreatableFromNameTypeAndDescriptionInterface>>
     */
    private const NAMED_AND_TYPED_TAGS = [
        'global' => GlobalTag::class,
        'param' => ParamTag::class,
        'property' => PropertyTag::class,
        'property-read' => PropertyReadTag::class,
        'property-write' => PropertyWriteTag::class,
    ];

    public function __construct(
        private readonly Parser $parser,
    ) {}

    /**
     * @return iterable<non-empty-lowercase-string, TagFactoryInterface>
     */
    private function getAllTags(): iterable
    {
        yield 'link' => new LinkTagFactory(LinkTag::class);
        yield 'see' => new LinkTagFactory(SeeTag::class);

        foreach (self::COMMON_TAGS as $name => $tag) {
            yield $name => new CreatableFromDescriptionTagFactory($tag);
        }

        foreach (self::TYPED_TAGS as $name => $tag) {
            yield $name => new CreatableFromTagAndDescriptionTagFactory($tag, $this->parser);
        }

        foreach (self::NAMED_AND_TYPED_TAGS as $name => $tag) {
            yield $name => new CreatableFromNameTypeAndDescriptionTagFactory($tag, $this->parser);
        }
    }

    /**
     * @return iterable<array-key, non-empty-lowercase-string>
     */
    private function getAllPrefixes(): iterable
    {
        return self::COMMON_PREFIXES;
    }

    /**
     * @param non-empty-lowercase-string $tag
     * @param non-empty-lowercase-string $prefix
     */
    private function isSupportedBy(string $tag, string $prefix): bool
    {
        return true;
    }

    /**
     * @return iterable<non-empty-lowercase-string, TagFactoryInterface>
     */
    public function getTags(): iterable
    {
        foreach ($this->getAllTags() as $tag => $factory) {
            yield $tag => $factory;

            foreach ($this->getAllPrefixes() as $prefix) {
                if (!$this->isSupportedBy($tag, $prefix)) {
                    continue;
                }

                yield "{$prefix}-{$tag}" => $factory;
            }
        }
    }
}
