<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Extractor;

use PHPUnit\Framework\Attributes\Before;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagTypeExtractor;
use TypeLang\PhpDocParser\Exception\InvalidTagTypeException;

final class TagTypeExtractorTest extends TestCase
{
    private TagTypeExtractor $extractor;

    #[Before]
    public function setUpTagNameExtractor(): void
    {
        $this->extractor = new TagTypeExtractor(
            parser: new Parser(true),
        );
    }

    public function testExtraction(): void
    {
        [$type, $description] = $this->extractor->extractTypeOrFail('type description');

        self::assertInstanceOf(NamedTypeNode::class, $type);
        self::assertSame('description', $description);
    }

    public function testWithoutDescription(): void
    {
        [$type, $description] = $this->extractor->extractTypeOrFail('type');

        self::assertInstanceOf(NamedTypeNode::class, $type);
        self::assertSame('type', $type->name->toString());

        self::assertNull($description);
    }

    public function testWithoutType(): void
    {
        self::expectException(InvalidTagTypeException::class);
        self::expectExceptionCode(InvalidTagTypeException::CODE_WITHOUT_NAME);

        $this->extractor->extractTypeOrFail(':test');
    }

    public function testWithoutTypeAllowMixed(): void
    {
        [$type, $description] = $this->extractor->extractTypeOrMixed(':test');

        self::assertInstanceOf(NamedTypeNode::class, $type);
        self::assertSame('mixed', $type->name->toString());

        self::assertSame(':test', $description);
    }

    public function testWithoutValue(): void
    {
        self::expectException(InvalidTagTypeException::class);
        self::expectExceptionCode(InvalidTagTypeException::CODE_WITHOUT_NAME);

        $this->extractor->extractTypeOrFail('');
    }

    public function testWithoutValueAllowMixed(): void
    {
        [$type, $description] = $this->extractor->extractTypeOrMixed('');

        self::assertInstanceOf(NamedTypeNode::class, $type);
        self::assertSame('mixed', $type->name->toString());

        self::assertNull($description);
    }
}
