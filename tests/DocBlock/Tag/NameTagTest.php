<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\NameCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\NameTag\NameTag;
use TypeLang\PhpDoc\DocBlock\Tag\NameTag\NameTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class NameTagTest extends TestCase
{
    #[Test]
    public function parsesAliasAndDescription(): void
    {
        $tag = self::factory()->create('name', 'globalConfig The shared configuration.');

        self::assertInstanceOf(NameTag::class, $tag);
        self::assertSame('globalConfig', $tag->alias);
        self::assertSame('The shared configuration.', (string) $tag->description);
        self::assertSame('@name globalConfig The shared configuration.', (string) $tag);
    }

    #[Test]
    public function parsesAliasOnly(): void
    {
        $tag = self::factory()->create('name', 'homepage');

        self::assertInstanceOf(NameTag::class, $tag);
        self::assertSame('homepage', $tag->alias);
        self::assertNull($tag->description);
        self::assertSame('@name homepage', (string) $tag);
    }

    #[Test]
    public function rejectsMissingAlias(): void
    {
        $tag = self::factory()->create('name', '');

        self::assertInstanceOf(InvalidTag::class, $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @name homepage */');

        self::assertInstanceOf(NameTag::class, $block->tags[0]);
        self::assertSame('homepage', $block->tags[0]->alias);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            NameTagDefinition::NAME => new NameTagDefinition(),
        ]);

        return new TagFactory($registry, [
            NameCombinator::NAME => new NameCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
