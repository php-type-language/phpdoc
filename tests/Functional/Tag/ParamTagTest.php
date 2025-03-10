<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Functional\Tag;

use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\ParamTag\ParamTag;

final class ParamTagTest extends TagTestCase
{
    public function testEmptyTag(): void
    {
        $tag = $this->parseTag('@param');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @param contains an incorrect type', $tag->reason->getMessage());
        self::assertNull($tag->description);
    }

    public function testTagWithInvalidType(): void
    {
        $tag = $this->parseTag('@param <example>');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @param contains an incorrect type', $tag->reason->getMessage());
        self::assertEquals(new Description('<example>'), $tag->description);
    }

    public function testWithType(): void
    {
        $tag = $this->parseTag('@param Link\To\Type');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @param contains an incorrect variable name', $tag->reason->getMessage());
        self::assertEquals(new Description('Link\To\Type'), $tag->description);
    }

    public function testWithTypeAndDescription(): void
    {
        $tag = $this->parseTag('@param type description');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @param contains an incorrect variable name', $tag->reason->getMessage());
        self::assertEquals(new Description('type description'), $tag->description);
    }

    public function testWithVariable(): void
    {
        $tag = $this->parseTag('@param $var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithTypeAndVariable(): void
    {
        $tag = $this->parseTag('@param type $var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param $var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithTypeAndVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param type $var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithVariadicVariable(): void
    {
        $tag = $this->parseTag('@param ...$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithTypeAndVariadicVariable(): void
    {
        $tag = $this->parseTag('@param type ...$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithVariadicVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param ...$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithTypeAndVariadicVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param type ...$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertFalse($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithOutputVariable(): void
    {
        $tag = $this->parseTag('@param &$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithTypeAndOutputVariable(): void
    {
        $tag = $this->parseTag('@param type &$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithOutputVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param &$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithTypeAndOutputVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param type &$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertFalse($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithOutputVariadicVariable(): void
    {
        $tag = $this->parseTag('@param &...$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithTypeAndOutputVariadicVariable(): void
    {
        $tag = $this->parseTag('@param type &...$var');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertNull($tag->description);
    }

    public function testWithOutputVariadicVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param &...$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNull($tag->type);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }

    public function testWithTypeAndOutputVariadicVariableAndDescription(): void
    {
        $tag = $this->parseTag('@param type &...$var description');

        self::assertInstanceOf(ParamTag::class, $tag);
        self::assertNotNull($tag->type);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
        self::assertSame('var', $tag->variable);
        self::assertTrue($tag->isOutput);
        self::assertTrue($tag->isVariadic);
        self::assertEquals(new Description('description'), $tag->description);
    }
}
