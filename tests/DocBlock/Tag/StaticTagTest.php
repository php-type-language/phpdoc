<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\StaticTag\StaticTag;
use TypeLang\PhpDoc\DocBlock\Tag\StaticTag\StaticTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class StaticTagTest extends TestCase
{
    #[Test]
    public function parsesDescription(): void
    {
        $tag = self::factory()->create('static', 'Kept for backward compatibility.');

        self::assertInstanceOf(StaticTag::class, $tag);
        self::assertSame('Kept for backward compatibility.', (string) $tag->description);
        self::assertSame('@static Kept for backward compatibility.', (string) $tag);
    }

    #[Test]
    public function parsesWithoutDescription(): void
    {
        $tag = self::factory()->create('static', '');

        self::assertInstanceOf(StaticTag::class, $tag);
        self::assertNull($tag->description);
        self::assertSame('@static', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @static */');

        self::assertInstanceOf(StaticTag::class, $block->tags[0]);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: [
                StaticTagDefinition::NAME => new StaticTagDefinition(),
            ],
            combinators: [
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
