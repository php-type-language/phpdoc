<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Parser;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\TypeReader;
use TypeLang\PhpDoc\Parser\Exception\InvalidTypeException;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
final class TypeReaderTest extends TestCase
{
    protected function getReader(): TypeReader
    {
        return new TypeReader(
            parser: new Parser(true),
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
        self::expectException(InvalidTypeException::class);
        self::expectExceptionCode(InvalidTypeException::CODE_WITHOUT_TYPE);

        $this->read(':type');
    }

    public function testEmpty(): void
    {
        self::expectException(InvalidTypeException::class);
        self::expectExceptionCode(InvalidTypeException::CODE_WITHOUT_TYPE);

        $this->read('');
    }
}
