<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\Description\DescriptionFactory;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\TagFactory\LinkTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactorySelector;
use TypeLang\PhpDocParser\DocBlock\Tag\ApiTag;
use TypeLang\PhpDocParser\DocBlock\Tag\AuthorTag;
use TypeLang\PhpDocParser\DocBlock\TagFactory\CommonTypedTagWithNameFactory;
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
use TypeLang\PhpDocParser\DocBlock\TagFactory\CommonTagFactory;
use TypeLang\PhpDocParser\DocBlock\TagFactory\CommonTypedTagFactory;
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
use JetBrains\PhpStorm\Language;

/**
 * @psalm-suppress UndefinedAttributeClass : JetBrains language attribute may not be available
 */
final class DocBlockFactory implements DocBlockFactoryInterface
{
    /**
     * @param TagFactoryInterface<TagInterface> $tags
     */
    public function __construct(
        public readonly DescriptionFactoryInterface $descriptions,
        public readonly TagFactoryInterface $tags,
    ) {}

    public static function createInstance(?Parser $parser = null): self
    {
        $tags = new TagFactorySelector();

        foreach (self::getStandardPrefixes() as $prefix) {
            $tags->addPrefix($prefix);
        }

        $descriptions = new DescriptionFactory($tags);

        foreach (self::getStandardTags($parser) as $tag => $factory) {
            if ($factory->getDescriptionFactory() === null) {
                $factory = $factory->withDescriptionFactory($descriptions);
            }

            $tags->add($factory, ...(array)$tag);
        }

        return new self(
            descriptions: $descriptions,
            tags: $tags->withDescriptionFactory($descriptions),
        );
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
    public static function getStandardTags(?Parser $parser = null): iterable
    {
        $parser ??= new Parser(true);

        if (!$parser->tolerant) {
            throw new \InvalidArgumentException('Tolerant parser mode required');
        }

        // Typed doc blocks
        yield 'var' => new CommonTypedTagFactory(VarTag::class, $parser);
        yield 'global' => new CommonTypedTagWithNameFactory(GlobalTag::class, $parser);
        yield 'param' => new CommonTypedTagWithNameFactory(ParamTag::class, $parser);
        yield 'property' => new CommonTypedTagWithNameFactory(PropertyTag::class, $parser);
        yield 'property-read' => new CommonTypedTagWithNameFactory(PropertyReadTag::class, $parser);
        yield 'property-write' => new CommonTypedTagWithNameFactory(PropertyWriteTag::class, $parser);
        yield ['return', 'returns'] => new CommonTypedTagFactory(ReturnTag::class, $parser);
        yield ['throw', 'throws'] => new CommonTypedTagFactory(ThrowsTag::class, $parser);

        // Common doc blocks
        yield 'api' => new CommonTagFactory(ApiTag::class);
        yield 'author' => new CommonTagFactory(AuthorTag::class);
        yield ['category', 'package', 'subpackage'] => new CommonTagFactory(PackageTag::class);
        yield 'copyright' => new CommonTagFactory(CopyrightTag::class);
        yield 'deprecated' => new CommonTagFactory(DeprecatedTag::class);
        yield 'example' => new CommonTagFactory(ExampleTag::class);
        yield 'filesource' => new CommonTagFactory(FilesourceTag::class);
        yield 'final' => new CommonTagFactory(FinalTag::class);
        yield 'ignore' => new CommonTagFactory(IgnoreTag::class);
        yield 'immutable' => new CommonTagFactory(ImmutableTag::class);
        yield 'inheritdoc' => new CommonTagFactory(InheritDocTag::class);
        yield 'internal' => new CommonTagFactory(InternalTag::class);
        yield 'license' => new CommonTagFactory(LicenseTag::class);
        yield 'method' => new CommonTagFactory(MethodTag::class);
        yield 'no-named-arguments' => new CommonTagFactory(NoNamedArgumentsTag::class);
        yield 'since' => new CommonTagFactory(SinceTag::class);
        yield 'source' => new CommonTagFactory(SourceTag::class);
        yield 'todo' => new CommonTagFactory(TodoTag::class);
        yield ['used-by', 'usedby'] => new CommonTagFactory(UsedByTag::class);
        yield 'uses' => new CommonTagFactory(UsesTag::class);
        yield 'version' => new CommonTagFactory(VersionTag::class);

        yield 'see' => new LinkTagFactory(SeeTag::class);
        yield 'link' => new LinkTagFactory(LinkTag::class);
    }

    public function create(#[Language('PHP')] string $docblock): DocBlock
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
                $description .= "\n@{$tag}";
                continue;
            }

            $tags[] = $this->tags->create("@{$tag}");
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
            pattern: '/[ \t]*(?:\/\*+|\*+\/|\*+)?[ \t]?(.*)?/u',
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
