<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Grammar;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\DocBlock\Combinator\TypeCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use TypeLang\Type\NamedTypeNode;
use TypeLang\Type\NullableTypeNode;
use TypeLang\Type\UnionTypeNode;

final class TypeGrammarRuleTest extends GrammarRuleTestCase
{
    protected function rule(): TypeCombinator
    {
        return new TypeCombinator(new TypeParser());
    }

    #[Test]
    public function producesANamedType(): void
    {
        $statement = $this->matchText('array<int, string>');

        self::assertInstanceOf(TypeReference::class, $statement);
        self::assertInstanceOf(NamedTypeNode::class, $statement->type);
        self::assertSame('array', (string) $statement->type->name);
    }

    /**
     * The parsed type keeps the exact text it was read from.
     */
    #[Test]
    public function preservesTheSourceText(): void
    {
        $statement = $this->matchText('array<int, string>');

        self::assertInstanceOf(TypeReference::class, $statement);
        self::assertSame('array<int, string>', $statement->source);
        self::assertSame('array<int, string>', (string) $statement);
    }

    #[Test]
    public function producesANullableType(): void
    {
        $statement = $this->matchText('?string');

        self::assertInstanceOf(NullableTypeNode::class, $statement->type);
    }

    #[Test]
    public function producesAUnionType(): void
    {
        $statement = $this->matchText('int|string');

        self::assertInstanceOf(UnionTypeNode::class, $statement->type);
    }

    /**
     * Only the type is consumed, the trailing description stays for the next
     * rule.
     */
    #[Test]
    public function stopsAfterTheType(): void
    {
        $cursor = new Cursor('int|string and the rest');
        $statement = $this->matchCursor($cursor);

        self::assertInstanceOf(UnionTypeNode::class, $statement->type);
        self::assertSame('int|string', $statement->source);
        self::assertSame(11, $cursor->offset);
    }

    /**
     * The consumed offset is rebased onto the source when the cursor does not
     * start at zero.
     */
    #[Test]
    public function respectsTheCursorBase(): void
    {
        $cursor = new Cursor('int rest', base: 100);
        $statement = $this->matchCursor($cursor);

        self::assertInstanceOf(TypeReference::class, $statement);
        self::assertSame(104, $cursor->offset);
    }

    #[Test]
    public function rejectsAnEmptyInput(): void
    {
        $this->expectException(NoMatchException::class);

        $this->matchText('');
    }
}
