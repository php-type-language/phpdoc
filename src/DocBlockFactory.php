<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use TypeLang\Parser\Parser;
use TypeLang\Parser\ParserInterface;
use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactory;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\StandardTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\ApiTag;
use TypeLang\PhpDocParser\DocBlock\Tag\AuthorTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ExampleTag;
use TypeLang\PhpDocParser\DocBlock\Tag\FilesourceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\GlobalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\IgnoreTag;
use TypeLang\PhpDocParser\DocBlock\Tag\LicenseTag;
use TypeLang\PhpDocParser\DocBlock\Tag\LinkTag;
use TypeLang\PhpDocParser\DocBlock\Tag\MethodTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PackageTag;
use TypeLang\PhpDocParser\DocBlock\Tag\CopyrightTag;
use TypeLang\PhpDocParser\DocBlock\Tag\DeprecatedTag;
use TypeLang\PhpDocParser\DocBlock\Tag\FinalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\CommonTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\CommonTypedTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\ImmutableTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InheritDocTag;
use TypeLang\PhpDocParser\DocBlock\Tag\InternalTag;
use TypeLang\PhpDocParser\DocBlock\Tag\NoNamedArgumentsTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ParamTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyReadTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyTag;
use TypeLang\PhpDocParser\DocBlock\Tag\PropertyWriteTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ReturnTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SeeTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SinceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\SourceTag;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\ThrowsTag;
use TypeLang\PhpDocParser\DocBlock\Tag\TodoTag;
use TypeLang\PhpDocParser\DocBlock\Tag\UsedByTag;
use TypeLang\PhpDocParser\DocBlock\Tag\UsesTag;
use TypeLang\PhpDocParser\DocBlock\Tag\VarTag;
use TypeLang\PhpDocParser\DocBlock\Tag\VersionTag;
use TypeLang\PhpDocParser\DocBlock\TagFactoryInterface;

final class DocBlockFactory implements DocBlockFactoryInterface
{
    /**
     * @param TagFactoryInterface<TagInterface> $tags
     */
    public function __construct(
        public readonly DescriptionFactoryInterface $descriptions,
        public readonly TagFactoryInterface $tags,
    ) {}

    public static function createInstance(?ParserInterface $parser = null): self
    {
        $tags = new StandardTagFactory();

        foreach (self::getStandardPrefixes() as $prefix) {
            $tags->addPrefix($prefix);
        }

        $descriptions = new DescriptionFactory($tags);

        foreach (self::getStandardTags($descriptions, $parser) as $tag => $factory) {
            $tags->add($factory, ...(array)$tag);
        }

        return new self($descriptions, $tags);
    }

    /**
     * @return iterable<array-key, non-empty-lowercase-string>
     */
    public static function getStandardPrefixes(): iterable
    {
        yield 'psalm';
        yield 'phpstan';
        yield 'phan';
    }

    /**
     * @return iterable<non-empty-lowercase-string|list<non-empty-lowercase-string>, TagFactoryInterface>
     */
    public static function getStandardTags(
        DescriptionFactoryInterface $descriptions,
        ?ParserInterface $parser = null
    ): iterable {
        $parser ??= new Parser(true);

        // Typed doc blocks
        yield 'var' => new CommonTypedTagFactory(VarTag::class, $parser, $descriptions);
        yield 'global' => new CommonTypedTagFactory(GlobalTag::class, $parser, $descriptions);
        yield 'param' => new CommonTypedTagFactory(ParamTag::class, $parser, $descriptions);
        yield 'property' => new CommonTypedTagFactory(PropertyTag::class, $parser, $descriptions);
        yield 'property-read' => new CommonTypedTagFactory(PropertyReadTag::class, $parser, $descriptions);
        yield 'property-write' => new CommonTypedTagFactory(PropertyWriteTag::class, $parser, $descriptions);
        yield ['return', 'returns'] => new CommonTypedTagFactory(ReturnTag::class, $parser, $descriptions);
        yield ['throw', 'throws'] => new CommonTypedTagFactory(ThrowsTag::class, $parser, $descriptions);

        // Common doc blocks
        yield 'api' => new CommonTagFactory(ApiTag::class, $descriptions);
        yield 'author' => new CommonTagFactory(AuthorTag::class, $descriptions);
        yield ['category', 'package', 'subpackage'] => new CommonTagFactory(PackageTag::class, $descriptions);
        yield 'copyright' => new CommonTagFactory(CopyrightTag::class, $descriptions);
        yield 'deprecated' => new CommonTagFactory(DeprecatedTag::class, $descriptions);
        yield 'example' => new CommonTagFactory(ExampleTag::class, $descriptions);
        yield 'filesource' => new CommonTagFactory(FilesourceTag::class, $descriptions);
        yield 'final' => new CommonTagFactory(FinalTag::class, $descriptions);
        yield 'ignore' => new CommonTagFactory(IgnoreTag::class, $descriptions);
        yield 'immutable' => new CommonTagFactory(ImmutableTag::class, $descriptions);
        yield 'inheritdoc' => new CommonTagFactory(InheritDocTag::class, $descriptions);
        yield 'internal' => new CommonTagFactory(InternalTag::class, $descriptions);
        yield 'license' => new CommonTagFactory(LicenseTag::class, $descriptions);
        yield 'link' => new CommonTagFactory(LinkTag::class, $descriptions);
        yield 'method' => new CommonTagFactory(MethodTag::class, $descriptions);
        yield 'no-named-arguments' => new CommonTagFactory(NoNamedArgumentsTag::class, $descriptions);
        yield 'see' => new CommonTagFactory(SeeTag::class, $descriptions);
        yield 'since' => new CommonTagFactory(SinceTag::class, $descriptions);
        yield 'source' => new CommonTagFactory(SourceTag::class, $descriptions);
        yield 'todo' => new CommonTagFactory(TodoTag::class, $descriptions);
        yield ['used-by', 'usedby'] => new CommonTagFactory(UsedByTag::class, $descriptions);
        yield 'uses' => new CommonTagFactory(UsesTag::class, $descriptions);
        yield 'version' => new CommonTagFactory(VersionTag::class, $descriptions);
    }

    public function create(string $docblock): DocBlock
    {
        $docblock = $this->stripDocComment($docblock);
        $docblock = $this->normalizeLineDelimiters($docblock);

        [$description, $tags] = $this->splitByDocBlockTags($docblock);

        return new DocBlock($description, $tags);
    }

    /**
     * @return array{Description, list<TagInterface>}
     */
    private function splitByDocBlockTags(string $docblock): array
    {
        $description = '';
        $tags = [];

        foreach (\explode("\n@", $docblock) as $i => $tag) {
            if ($i === 0) {
                if (\str_starts_with($tag, '@')) {
                    $tags[] = $this->tags->create($tag);
                } else {
                    $description = $tag;
                }

                continue;
            }

            if (\str_starts_with($tag, ' ')) {
                $description .= "\n@$tag";
                continue;
            }

            $tags[] = $this->tags->create("@$tag");
        }

        return [$this->descriptions->create($description), $tags];
    }

    private function normalizeLineDelimiters(string $docblock): string
    {
        return \str_replace(["\r\n", "\r"], "\n", $docblock);
    }

    private function stripDocComment(string $docblock): string
    {
        if ($docblock === '') {
            return '';
        }

        $docblock = \preg_replace(
            pattern: '/[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]?(.*)?/u',
            replacement: '$1',
            subject: $docblock,
        )
            ?: $docblock;

        if (\str_ends_with($docblock, '*/')) {
            $docblock = \substr($docblock, 0, -2);
        }

        return \trim($docblock);
    }
}
