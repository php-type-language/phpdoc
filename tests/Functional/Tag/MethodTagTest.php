<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Functional\Tag;

use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Stmt\Callable\CallableParameterNode;
use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\MethodTag\MethodTag;

final class MethodTagTest extends TagTestCase
{
    public function testEmptyTag(): void
    {
        $tag = $this->parseTag('@method');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @method expects the type to be defined', $tag->reason->getMessage());
        self::assertNull($tag->description);
    }

    public function testTagWithModifier(): void
    {
        $tag = $this->parseTag('@method static');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @method must contain the method signature', $tag->reason->getMessage());
        self::assertEquals(new Description('static'), $tag->description);
    }

    public function testTagWithDescription(): void
    {
        $tag = $this->parseTag('@method description');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @method must contain the method signature', $tag->reason->getMessage());
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testTagWithModifierAndDescription(): void
    {
        $tag = $this->parseTag('@method static description');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @method must contain the method signature', $tag->reason->getMessage());
        self::assertEquals(new Description('static description'), $tag->description);
    }

    public function testTagWithInvalidCallableName(): void
    {
        $tag = $this->parseTag('@method Foo\Some\foo()');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @method must contain the method name, but FQN "Foo\Some\foo" given', $tag->reason->getMessage());
        self::assertEquals(new Description('Foo\Some\foo()'), $tag->description);
    }

    public function testTagWithCallable(): void
    {
        $tag = $this->parseTag('@method foo()');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertSame([], $tag->parameters);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithCallableAndDescription(): void
    {
        $tag = $this->parseTag('@method foo() description');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertSame([], $tag->parameters);
        self::assertFalse($tag->isStatic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testTagWithModifierAndCallable(): void
    {
        $tag = $this->parseTag('@method static foo()');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertSame([], $tag->parameters);
        self::assertTrue($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithModifierAndCallableAndDescription(): void
    {
        $tag = $this->parseTag('@method static foo() description');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertSame([], $tag->parameters);
        self::assertTrue($tag->isStatic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testTagWithTypeAndCallable(): void
    {
        $tag = $this->parseTag('@method Type foo()');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(Type)
                Identifier(Type)
            TYPE);
        self::assertSame([], $tag->parameters);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithDefaultTypeAndModifierAndCallable(): void
    {
        $tag = $this->parseTag('@method static static foo()');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(static)
                Identifier(static)
            TYPE);
        self::assertSame([], $tag->parameters);
        self::assertTrue($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithAlternativeTypeAndModifierAndCallable(): void
    {
        $tag = $this->parseTag('@method static foo(): static');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(static)
                Identifier(static)
            TYPE);
        self::assertSame([], $tag->parameters);
        self::assertTrue($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithDefaultAndAlternativeTypeAndModifierAndCallable(): void
    {
        $tag = $this->parseTag('@method static static foo(): static');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('You can specify the return type of the '
            . '@method tag before or after the method`s signature, but not both', $tag->reason->getMessage());
        self::assertEquals(new Description('static static foo(): static'), $tag->description);
    }

    public function testTagWithCallableAndNamedParameter(): void
    {
        $tag = $this->parseTag('@method foo($param)');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertTypeStatementSame($tag->parameters, <<<'TYPE'
            Stmt\Callable\CallableParameterNode(simple)
              Literal\VariableLiteralNode($param)
            TYPE);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithCallableAndTypedNamedParameter(): void
    {
        $tag = $this->parseTag('@method foo(Type $param)');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertTypeStatementSame($tag->parameters, <<<'TYPE'
            Stmt\Callable\CallableParameterNode(simple)
              Stmt\NamedTypeNode
                Name(Type)
                  Identifier(Type)
              Literal\VariableLiteralNode($param)
            TYPE);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithCallableAndTypedParameter(): void
    {
        $tag = $this->parseTag('@method foo(Type)');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNull($tag->type);
        self::assertTypeStatementSame($tag->parameters, <<<'TYPE'
            Stmt\Callable\CallableParameterNode(simple)
              Stmt\NamedTypeNode
                Name(Type)
                  Identifier(Type)
            TYPE);
        self::assertFalse($tag->isStatic);
        self::assertNull($tag->description);
    }

    public function testTagWithFullDefinition(): void
    {
        $tag = $this->parseTag('@method static ReturnType foo(Type $param) Example');

        self::assertInstanceOf(MethodTag::class, $tag);
        self::assertSame('foo', $tag->method);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(ReturnType)
                Identifier(ReturnType)
            TYPE);
        self::assertTypeStatementSame($tag->parameters, <<<'TYPE'
            Stmt\Callable\CallableParameterNode(simple)
              Stmt\NamedTypeNode
                Name(Type)
                  Identifier(Type)
              Literal\VariableLiteralNode($param)
            TYPE);
        self::assertTrue($tag->isStatic);
        self::assertEquals(new Description('Example'), $tag->description);
    }
}
