<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactory;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\StandardTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\AuthorTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\DeprecatedTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\FinalTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\ImmutableTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\InternalTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\NoNamedArgumentsTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\ParamTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\ReturnTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\ThrowsTag;
use TypeLang\PhpDocParser\DocBlock\Tag\ThrowsTagFactory;
use TypeLang\PhpDocParser\DocBlock\Tag\VarTagFactory;
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

    public static function createInstance(
        ExceptionHandlerInterface $exceptions = new VoidExceptionHandler(),
    ): self {
        $tags = new StandardTagFactory();

        foreach (self::getStandardPrefixes() as $prefix) {
            $tags->addPrefix($prefix);
        }

        $descriptions = new DescriptionFactory($tags);

        foreach (self::getStandardTags($descriptions, $exceptions) as $tag => $factory) {
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
    }

    /**
     * @return iterable<non-empty-lowercase-string|list<non-empty-lowercase-string>, TagFactoryInterface>
     */
    public static function getStandardTags(
        DescriptionFactoryInterface $descriptions,
        ExceptionHandlerInterface $exceptions = new VoidExceptionHandler(),
    ): iterable {
        $parser = new Parser(true);

        // Typed doc blocks
        yield 'var' => new VarTagFactory($exceptions, $parser, $descriptions);
        yield 'return' => new ReturnTagFactory($exceptions, $parser, $descriptions);
        yield 'param' => new ParamTagFactory($exceptions, $parser, $descriptions);
        yield ['throw', 'throws'] => new ThrowsTagFactory($exceptions, $parser, $descriptions);

        // Common doc blocks
        yield 'author' => new AuthorTagFactory($descriptions);
        yield 'final' => new FinalTagFactory($descriptions);
        yield 'deprecated' => new DeprecatedTagFactory($descriptions);
        yield 'immutable' => new ImmutableTagFactory($descriptions);
        yield 'internal' => new InternalTagFactory($descriptions);
        yield 'no-named-arguments' => new NoNamedArgumentsTagFactory($descriptions);
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
        $description = new Description();
        $tags = [];

        foreach (\explode("\n@", $docblock) as $i => $tag) {
            if ($i === 0) {
                if (\str_starts_with($tag, '@')) {
                    $tags[] = $this->tags->create($tag);
                } else {
                    $description = $this->descriptions->create($tag);
                }

                continue;
            }

            $tags[] = $this->tags->create("@$tag");
        }

        return [$description, $tags];
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

        return \trim($docblock);
    }
}
