<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Functional\Tag;

use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\VarTag\VarTag;

final class VarTagTest extends TagTestCase
{
    public function testEmptyTag(): void
    {
        $tag = $this->parseTag('@var');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @var expects the type to be defined', $tag->reason->getMessage());
        self::assertNull($tag->description);
    }

    public function testTagWithInvalidType(): void
    {
        $tag = $this->parseTag('@var <example>');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertSame('Tag @var contains an incorrect type "<example>"', $tag->reason->getMessage());
        self::assertEquals(new Description('<example>'), $tag->description);
    }

    public function testWithType(): void
    {
        $tag = $this->parseTag('@var Link\To\Type');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertNull($tag->variable);
        self::assertNull($tag->description);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(Link\To\Type)
                Identifier(Link)
                Identifier(To)
                Identifier(Type)
            TYPE);
    }

    public function testWithTypeAndDescription(): void
    {
        $tag = $this->parseTag('@var type description');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertNull($tag->variable);
        self::assertEquals(new Description('description'), $tag->description);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
    }

    public function testWithTypeAndVariable(): void
    {
        $tag = $this->parseTag('@var type $var');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertSame('var', $tag->variable);
        self::assertNull($tag->description);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
    }

    public function testWithTypeAndVariableAndDescription(): void
    {
        $tag = $this->parseTag('@var type $var description');

        self::assertInstanceOf(VarTag::class, $tag);
        self::assertSame('var', $tag->variable);
        self::assertEquals(new Description('description'), $tag->description);
        self::assertTypeStatementSame($tag->type, <<<'TYPE'
            Stmt\NamedTypeNode
              Name(type)
                Identifier(type)
            TYPE);
    }
}
