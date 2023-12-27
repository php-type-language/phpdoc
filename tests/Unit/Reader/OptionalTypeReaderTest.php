<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Reader\OptionalTypeReader;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
final class OptionalTypeReaderTest extends TestCase
{
    protected function getReader(): OptionalTypeReader
    {
        return new OptionalTypeReader(
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
        self::assertNull($this->read(':type'));
    }

    public function testEmpty(): void
    {
        self::assertNull($this->read(''));
    }
}
