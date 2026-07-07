<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\AccessCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\AccessTag;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\AccessTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\AccessTag\Visibility;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class AccessTagTest extends TestCase
{
    #[Test]
    public function parsesVisibilityAndDescription(): void
    {
        $tag = self::factory()->create('access', 'protected Internal API.');

        self::assertInstanceOf(AccessTag::class, $tag);
        self::assertSame(Visibility::Protected, $tag->access);
        self::assertSame('Internal API.', (string) $tag->description);
        self::assertSame('@access protected Internal API.', (string) $tag);
    }

    #[Test]
    public function parsesVisibilityOnly(): void
    {
        $tag = self::factory()->create('access', 'private');

        self::assertInstanceOf(AccessTag::class, $tag);
        self::assertSame(Visibility::Private, $tag->access);
        self::assertNull($tag->description);
        self::assertSame('@access private', (string) $tag);
    }

    #[Test]
    public function rejectsUnknownVisibility(): void
    {
        $tag = self::factory()->create('access', 'package');

        self::assertInstanceOf(InvalidTag::class, $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @access public */');

        self::assertInstanceOf(AccessTag::class, $block->tags[0]);
        self::assertSame(Visibility::Public, $block->tags[0]->access);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            AccessTagDefinition::NAME => new AccessTagDefinition(),
        ]);

        return new TagFactory(
            $registry,
            [
                AccessCombinator::NAME => new AccessCombinator(),
                DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
            ],
        );
    }
}
