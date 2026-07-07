<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTag;
use TypeLang\PhpDoc\DocBlock\Tag\AbstractTag\AbstractTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\FlagTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTag;
use TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag\ImmutableTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTag;
use TypeLang\PhpDoc\DocBlock\Tag\InternalTag\InternalTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag\NotDeprecatedTag;
use TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag\NotDeprecatedTagDefinition;
use TypeLang\PhpDoc\DocBlock\Tag\TodoTag\TodoTag;
use TypeLang\PhpDoc\DocBlock\Tag\TodoTag\TodoTagDefinition;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagPlacement;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class FlagTagTest extends TestCase
{
    #[Test]
    public function parsesWithoutDescription(): void
    {
        $tag = self::factory()->create('abstract', '');

        self::assertInstanceOf(AbstractTag::class, $tag);
        self::assertSame('abstract', $tag->name);
        self::assertNull($tag->description);
        self::assertSame('@abstract', (string) $tag);
    }

    #[Test]
    public function parsesTrailingTextAsDescription(): void
    {
        $tag = self::factory()->create('todo', 'Rewrite this using the new API.');

        self::assertInstanceOf(TodoTag::class, $tag);
        self::assertSame('Rewrite this using the new API.', (string) $tag->description);
        self::assertSame('@todo Rewrite this using the new API.', (string) $tag);
    }

    #[Test]
    public function placementIsRecognized(): void
    {
        self::assertSame(TagPlacement::Any, new InternalTagDefinition()->placement);
        self::assertSame(TagPlacement::Block, new AbstractTagDefinition()->placement);
    }

    /**
     * @return iterable<string, array{string, class-string<FlagTagInterface>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@abstract' => ['abstract', AbstractTag::class];
        yield '@internal' => ['internal', InternalTag::class];
        yield '@todo' => ['todo', TodoTag::class];
        yield '@immutable' => ['immutable', ImmutableTag::class];
        yield '@not-deprecated' => ['not-deprecated', NotDeprecatedTag::class];
    }

    /**
     * @param class-string<FlagTagInterface> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function tagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            AbstractTagDefinition::NAME => new AbstractTagDefinition(),
            InternalTagDefinition::NAME => new InternalTagDefinition(),
            TodoTagDefinition::NAME => new TodoTagDefinition(),
            ImmutableTagDefinition::NAME => new ImmutableTagDefinition(),
            NotDeprecatedTagDefinition::NAME => new NotDeprecatedTagDefinition(),
        ]);

        return new TagFactory($registry, [
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
