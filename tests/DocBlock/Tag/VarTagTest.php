<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTag;
use TypeLang\PhpDoc\DocBlock\Tag\VarTag\VarTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class VarTagTest extends TestCase
{
    #[Test]
    public function parsesTypeOnly(): void
    {
        $tag = self::factory()->create('var', 'non-empty-string');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertNull($tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@var non-empty-string', (string) $tag);
    }

    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::factory()->create('var', 'list<int> $items The queued items.');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertSame('items', $tag->variable);
        self::assertSame('The queued items.', (string) $tag->description);
        self::assertSame('@var list<int> $items The queued items.', (string) $tag);
    }

    #[Test]
    public function parsesTypeAndDescriptionWithoutVariable(): void
    {
        $tag = self::factory()->create('var', 'int The counter.');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertNull($tag->variable);
        self::assertSame('The counter.', (string) $tag->description);
        self::assertSame('@var int The counter.', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @var \DateTimeInterface $date */');

        self::assertInstanceOf(VarTag::class, $block->tags[0]);
        self::assertSame('date', $block->tags[0]->variable);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            VarTagDefinition::NAME => new VarTagDefinition(),
        ]);

        return new TagFactory($registry, [
            TypeCombinator::NAME => new TypeCombinator(new TypeParser()),
            VariableCombinator::NAME => new VariableCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
