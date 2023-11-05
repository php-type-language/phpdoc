<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Extractor;

use PHPUnit\Framework\Attributes\Before;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagVariableExtractor;
use TypeLang\PhpDocParser\Exception\InvalidTagVariableNameException;

final class TagVariableExtractorTest extends TestCase
{
    private TagVariableExtractor $extractor;

    #[Before]
    public function setUpTagNameExtractor(): void
    {
        $this->extractor = new TagVariableExtractor();
    }

    public function testExtraction(): void
    {
        [$name, $description] = $this->extractor->extractOrFail('$name description');

        self::assertSame('$name', $name);
        self::assertSame('description', $description);
    }

    public function testWithoutDescription(): void
    {
        [$name, $description] = $this->extractor->extractOrFail('$name');

        self::assertSame('$name', $name);
        self::assertNull($description);
    }

    public function testWithInvalidName(): void
    {
        self::expectException(InvalidTagVariableNameException::class);
        self::expectExceptionCode(InvalidTagVariableNameException::CODE_WITHOUT_TYPE);

        $this->extractor->extractOrFail('$0_name');
    }

    public function testWithInvalidNameAllowNull(): void
    {
        [$var, $description] = $this->extractor->extractOrNull('$0_name');

        self::assertNull($var);
        self::assertSame('$0_name', $description);
    }

    public function testWithoutVariable(): void
    {
        self::expectException(InvalidTagVariableNameException::class);
        self::expectExceptionCode(InvalidTagVariableNameException::CODE_WITHOUT_TYPE);

        $this->extractor->extractOrFail('test');
    }

    public function testWithoutVariableAllowNull(): void
    {
        [$var, $description] = $this->extractor->extractOrNull('test');

        self::assertNull($var);
        self::assertSame('test', $description);
    }

    public function testWithEmptyValue(): void
    {
        self::expectException(InvalidTagVariableNameException::class);
        self::expectExceptionCode(InvalidTagVariableNameException::CODE_WITHOUT_TYPE);

        $this->extractor->extractOrFail('');
    }

    public function testWithEmptyValueAllowNull(): void
    {
        [$var, $description] = $this->extractor->extractOrNull('');

        self::assertNull($var);
        self::assertNull($description);
    }

    public function testVariadic(): void
    {
        [$var, $description] = $this->extractor->extractOrFail('...$name description');

        self::assertSame('$name', $var);
        self::assertSame('description', $description);
    }
}
