<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\VariableCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag\StaticVarTag;
use TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag\StaticVarTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class StaticVarTagTest extends TestCase
{
    #[Test]
    public function parsesTypeVariableAndDescription(): void
    {
        $tag = self::factory()->create('staticvar', 'int $counter The call count.');

        self::assertInstanceOf(StaticVarTag::class, $tag);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('int', (string) $tag->type->name);
        self::assertSame('counter', $tag->variable);
        self::assertSame('The call count.', (string) $tag->description);
        self::assertSame('@staticvar int $counter The call count.', (string) $tag);
    }

    #[Test]
    public function parsesTypeOnly(): void
    {
        $tag = self::factory()->create('staticvar', 'string');

        self::assertInstanceOf(StaticVarTag::class, $tag);
        self::assertInstanceOf(NamedTypeNode::class, $tag->type);
        self::assertSame('string', (string) $tag->type->name);
        self::assertNull($tag->variable);
        self::assertNull($tag->description);
        self::assertSame('@staticvar string', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @staticvar int $cache */');

        self::assertInstanceOf(StaticVarTag::class, $block->tags[0]);
        self::assertSame('cache', $block->tags[0]->variable);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            StaticVarTagDefinition::NAME => new StaticVarTagDefinition(),
        ]);

        return new TagFactory($registry, [
            TypeCombinator::NAME => new TypeCombinator(new TypeParser()),
            VariableCombinator::NAME => new VariableCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
