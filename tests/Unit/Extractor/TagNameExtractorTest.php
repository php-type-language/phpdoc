<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Extractor;

use PHPUnit\Framework\Attributes\Before;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagNameExtractor;
use TypeLang\PhpDocParser\Exception\TagWithoutNameException;

final class TagNameExtractorTest extends TestCase
{
    private TagNameExtractor $extractor;

    #[Before]
    public function setUpTagNameExtractor(): void
    {
        $this->extractor = new TagNameExtractor();
    }

    public function testExtraction(): void
    {
        [$name, $description] = $this->extractor->extract('@name descr');

        self::assertSame('name', $name);
        self::assertSame('descr', $description);
    }

    public function testByNonNamespaceDelimiter(): void
    {
        [$name, $description] = $this->extractor->extract('@phpcs:on test');

        self::assertSame('phpcs:on', $name);
        self::assertSame('test', $description);
    }

    public function testWithoutDescription(): void
    {
        [$name, $description] = $this->extractor->extract('@inheritDoc');

        self::assertSame('inheritDoc', $name);
        self::assertNull($description);
    }

    public function testWithoutName(): void
    {
        self::expectException(TagWithoutNameException::class);
        self::expectExceptionCode(TagWithoutNameException::CODE_NO_NAME);

        $this->extractor->extract('@ Description');
    }

    public function testWithoutNameAndDescription(): void
    {
        self::expectException(TagWithoutNameException::class);
        self::expectExceptionCode(TagWithoutNameException::CODE_NO_NAME);

        $this->extractor->extract('@');
    }

    public function testEmptyTag(): void
    {
        self::expectException(TagWithoutNameException::class);
        self::expectExceptionCode(TagWithoutNameException::CODE_EMPTY);

        $this->extractor->extract('');
    }

    public function testWithoutTag(): void
    {
        self::expectException(TagWithoutNameException::class);
        self::expectExceptionCode(TagWithoutNameException::CODE_NON_TAGGED);

        $this->extractor->extract('description');
    }
}
