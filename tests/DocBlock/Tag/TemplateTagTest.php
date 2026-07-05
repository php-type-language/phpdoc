<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateContravariantTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateCovariantTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTag;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TemplateTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TemplateTag\TypeParameterTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class TemplateTagTest extends TestCase
{
    #[Test]
    public function parsesNameBoundDefaultAndDescription(): void
    {
        $tag = self::factory()->create('template', 'T of \Countable = array The item type.');

        self::assertInstanceOf(TemplateTag::class, $tag);
        self::assertInstanceOf(TypeParameterTag::class, $tag);
        self::assertSame('template', $tag->name);
        self::assertSame('T', $tag->parameter);
        self::assertNotNull($tag->bound);
        self::assertSame('\Countable', (string) $tag->bound);
        self::assertNotNull($tag->default);
        self::assertSame('array', (string) $tag->default);
        self::assertSame('The item type.', (string) $tag->description);
        self::assertSame('@template T of \Countable = array The item type.', (string) $tag);
    }

    #[Test]
    public function parsesBareName(): void
    {
        $tag = self::factory()->create('template', 'TValue');

        self::assertInstanceOf(TemplateTag::class, $tag);
        self::assertSame('TValue', $tag->parameter);
        self::assertNull($tag->bound);
        self::assertNull($tag->default);
        self::assertNull($tag->description);
        self::assertSame('@template TValue', (string) $tag);
    }

    #[Test]
    public function parsesBoundWithoutDefault(): void
    {
        $tag = self::factory()->create('template', 'T of string');

        self::assertSame('T', $tag->parameter);
        self::assertSame('string', (string) $tag->bound);
        self::assertNull($tag->default);
    }

    /**
     * A word that merely starts with "of" is not the "of" keyword, so it stays
     * part of the description.
     */
    #[Test]
    public function keywordRespectsWordBoundary(): void
    {
        $tag = self::factory()->create('template', 'T offset from the base');

        self::assertSame('T', $tag->parameter);
        self::assertNull($tag->bound);
        self::assertSame('offset from the base', (string) $tag->description);
    }

    #[Test]
    public function varianceTagsResolveThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse(<<<'PHPDOC'
            /**
             * @template-covariant T
             * @template-contravariant U of \Throwable
             */
            PHPDOC);

        self::assertInstanceOf(TemplateCovariantTag::class, $block->tags[0]);
        self::assertSame('template-covariant', $block->tags[0]->name);
        self::assertInstanceOf(TemplateContravariantTag::class, $block->tags[1]);
        self::assertSame('U', $block->tags[1]->parameter);
        self::assertSame('\Throwable', (string) $block->tags[1]->bound);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: [
                TemplateTagDefinition::NAME => new TemplateTagDefinition(),
            ],
            combinators: [
                NameCombinator::NAME => new NameCombinator(),
                TypeCombinator::NAME => new TypeCombinator(new TypeParser()),
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
