<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Provider;

use TypeLang\Parser\Parser;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ApiTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\AuthorTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CategoryTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CopyrightTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CreatableFromDescriptionInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CreatableFromNameTypeAndDescriptionInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CreatableFromTagAndDescriptionInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\DeprecatedTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ExampleTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\FilesourceTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\FinalTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\GlobalTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\IgnoreTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ImmutableTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\InheritDocTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\InternalTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\LicenseTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\LinkTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\MethodTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\NoNamedArgumentsTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\PackageTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ParamTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\PropertyReadTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\PropertyTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\PropertyWriteTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ReturnTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\SeeTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\SinceTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\SourceTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\SubPackageTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\ThrowsTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\TodoTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\VarTag;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\VersionTag;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\CreatableFromDescriptionTagFactory;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\CreatableFromNameTypeAndDescriptionTagFactory;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\CreatableFromTagAndDescriptionTagFactory;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\LinkTagFactory;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactory\TemplateTagFactory;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactoryInterface;

final class StandardTagProvider
{
    /**
     * - For psalm see {@link https://github.com/vimeo/psalm/blob/5.16.0/src/Psalm/DocComment.php#L21}.
     * - For phpstan see {@link https://github.com/phpstan/phpstan-src/blob/1.10.44/src/Rules/PhpDoc/InvalidPHPStanDocTagRule.php#L23}.
     * - For phan see {@link https://github.com/phan/phan/blob/5.4.2/src/Phan/Language/Element/Comment/Builder.php}.
     *
     * @var array<non-empty-lowercase-string, list<non-empty-lowercase-string>>
     */
    private const COMMON_PREFIXES = [
        'psalm' => [
            'return', 'param', 'template', 'var', 'type',
            'template-covariant', 'property', 'property-read', 'property-write', 'method',
            'assert', 'assert-if-true', 'assert-if-false', 'suppress',
            'ignore-nullable-return', 'override-property-visibility',
            'override-method-visibility', 'seal-properties', 'seal-methods',
            'no-seal-properties', 'no-seal-methods',
            'ignore-falsable-return', 'variadic', 'pure',
            'ignore-variable-method', 'ignore-variable-property', 'internal',
            'taint-sink', 'taint-source', 'assert-untainted', 'scope-this',
            'mutation-free', 'external-mutation-free', 'immutable', 'readonly',
            'allow-private-mutation', 'readonly-allow-private-mutation',
            'yield', 'trace', 'import-type', 'flow', 'taint-specialize', 'taint-escape',
            'taint-unescape', 'self-out', 'consistent-constructor', 'stub-override',
            'require-extends', 'require-implements', 'param-out', 'ignore-var',
            'consistent-templates', 'if-this-is', 'this-out', 'check-type', 'check-type-exact',
            'api', 'inheritors',
        ],
        'phpstan' => [
            'param', 'param-out', 'var', 'extends', 'implements', 'use',
            'template', 'template-contravariant', 'template-covariant',
            'return', 'throws', 'ignore-next-line', 'ignore-line', 'method',
            'pure', 'impure', 'immutable', 'type', 'import-type', 'property',
            'property-read', 'property-write', 'consistent-constructor', 'assert',
            'assert-if-true', 'assert-if-false', 'self-out', 'this-out',
            'allow-private-mutation', 'readonly', 'readonly-allow-private-mutation',
        ],
        'phan' => [
            'abstract', 'assert', 'assert', 'assert-false-condition',
            'assert-true-condition', 'closure-scope',
            'constructor-used-for-side-effects', 'extends',
            'external-mutation-free', 'file-suppress',
            'forbid-undeclared-magic-methods', 'forbid-undeclared-magic-properties',
            'hardcode-return-type', 'ignore-reference', 'immutable', 'inherits',
            'mandatory-param', 'method', 'mixin', 'output-reference', 'override',
            'param', 'property', 'property-read', 'property-write', 'pure',
            'read-only', 'real-return', 'real-throws', 'return',
            'side-effect-free', 'suppress', 'suppress-current-line',
            'suppress-next-line', 'suppress-next-next-line',
            'suppress-previous-line', 'template', 'type', 'unused-param',
            'var', 'write-only',
        ]
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
        'no-named-arguments' => NoNamedArgumentsTag::class,
        'package' => PackageTag::class,
        'since' => SinceTag::class,
        'source' => SourceTag::class,
        'subpackage' => SubPackageTag::class,
        'todo' => TodoTag::class,
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
        yield 'template' => new TemplateTagFactory($this->parser);

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
     * @return iterable<non-empty-lowercase-string, TagFactoryInterface>
     *
     * @psalm-suppress MoreSpecificReturnType : Psalm false-positive
     */
    public function getTags(): iterable
    {
        foreach ($this->getAllTags() as $tag => $factory) {
            yield $tag => $factory;

            foreach (self::COMMON_PREFIXES as $prefix => $tags) {
                if (!\in_array($tag, $tags, true)) {
                    continue;
                }

                yield "{$prefix}-{$tag}" => $factory;
            }
        }
    }
}
