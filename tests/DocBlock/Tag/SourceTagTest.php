<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IntegerCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\SourceTag\SourceTag;
use TypeLang\PhpDoc\DocBlock\Tag\SourceTag\SourceTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class SourceTagTest extends TestCase
{
    #[Test]
    public function parsesStartCountAndDescription(): void
    {
        $tag = self::factory()->create('source', '12 30 The relevant excerpt.');

        self::assertInstanceOf(SourceTag::class, $tag);
        self::assertSame(12, $tag->start);
        self::assertSame(30, $tag->count);
        self::assertSame('The relevant excerpt.', (string) $tag->description);
        self::assertSame('@source 12 30 The relevant excerpt.', (string) $tag);
    }

    #[Test]
    public function parsesStartOnly(): void
    {
        $tag = self::factory()->create('source', '7');

        self::assertInstanceOf(SourceTag::class, $tag);
        self::assertSame(7, $tag->start);
        self::assertNull($tag->count);
        self::assertNull($tag->description);
        self::assertSame('@source 7', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @source 3 5 */');

        self::assertInstanceOf(SourceTag::class, $block->tags[0]);
        self::assertSame(3, $block->tags[0]->start);
        self::assertSame(5, $block->tags[0]->count);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: [
                SourceTagDefinition::NAME => new SourceTagDefinition(),
            ],
            combinators: [
                IntegerCombinator::NAME => new IntegerCombinator(),
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
