<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Reader\OptionalTypeReader;
use TypeLang\PhpDocParser\DocBlock\Reader\TolerantTypeReader;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
final class TolerantTypeReaderTest extends TestCase
{
    protected function getReader(): TolerantTypeReader
    {
        return new TolerantTypeReader(
            reader: new OptionalTypeReader(
                parser: new Parser(true),
            ),
        );
    }

    public function testReading(): void
    {
        $sequence = $this->read('type');

        self::assertInstanceOf(NamedTypeNode::class, $sequence->data);
        self::assertSame(4, $sequence->offset);

        self::assertSame('type', $sequence->data->name->toString());
    }

    public function testWithDescription(): void
    {
        $sequence = $this->read('type description');

        self::assertInstanceOf(NamedTypeNode::class, $sequence->data);
        self::assertSame(5, $sequence->offset);

        self::assertSame('type', $sequence->data->name->toString());
    }

    public function testSyntaxError(): void
    {
        $sequence = $this->read(':type');

        self::assertInstanceOf(NamedTypeNode::class, $sequence->data);
        self::assertSame(0, $sequence->offset);

        self::assertSame('mixed', $sequence->data->name->toString());
    }

    public function testEmpty(): void
    {
        $sequence = $this->read('');

        self::assertInstanceOf(NamedTypeNode::class, $sequence->data);
        self::assertSame(0, $sequence->offset);

        self::assertSame('mixed', $sequence->data->name->toString());
    }
}
