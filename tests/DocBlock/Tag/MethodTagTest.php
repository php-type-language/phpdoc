<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\CallableTypeCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTag;
use TypeLang\PhpDoc\DocBlock\Tag\MethodTag\MethodTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;
use TypeLang\Type\NamedTypeNode;

final class MethodTagTest extends TestCase
{
    #[Test]
    public function parsesCallableCarryingItsOwnReturnType(): void
    {
        $tag = self::factory()->create('method', 'foo(T $t): void An optional description.');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', (string) $tag->method->name);
        self::assertFalse($tag->isStatic);
        self::assertNotNull($tag->method->type);
        self::assertSame('An optional description.', (string) $tag->description);
        self::assertSame('@method foo(T $t): void An optional description.', (string) $tag);
    }

    #[Test]
    public function parsesLeadingReturnTypeAndStatic(): void
    {
        $tag = self::factory()->create('method', 'static ReturnType bar(U $u) An optional description.');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('bar', (string) $tag->method->name);
        self::assertTrue($tag->isStatic);
        // The leading return type is grafted onto the callable.
        self::assertInstanceOf(NamedTypeNode::class, $tag->method->type);
        self::assertSame('ReturnType', (string) $tag->method->type->name);
        self::assertSame('@method static ReturnType bar(U $u) An optional description.', (string) $tag);
    }

    /**
     * A signature that is not a callable is malformed.
     */
    #[Test]
    public function rejectsNonCallableSignature(): void
    {
        $tag = self::factory()->create('method', 'JustAType');

        self::assertInstanceOf(InvalidTag::class, $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @method self withValue(mixed $value) */');

        self::assertInstanceOf(MethodTag::class, $block->tags[0]);
        self::assertSame('withValue', (string) $block->tags[0]->method->name);
    }

    private static function factory(): TagFactory
    {
        $types = new TypeParser();

        $registry = new TagRegistry([
            MethodTagDefinition::NAME => new MethodTagDefinition(),
        ]);

        return new TagFactory($registry, [
            TypeCombinator::NAME => new TypeCombinator($types),
            CallableTypeCombinator::NAME => new CallableTypeCombinator($types),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
