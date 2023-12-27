<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use TypeLang\PhpDocParser\DocBlock\Reader\VariableNameReader;
use TypeLang\PhpDocParser\Exception\InvalidVariableNameException;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
final class VariableNameReaderTest extends TestCase
{
    protected function getReader(): VariableNameReader
    {
        return new VariableNameReader();
    }

    public function testReading(): void
    {
        $sequence = $this->read('$name');

        self::assertSame('$name', $sequence->data);
        self::assertSame(5, $sequence->offset);
    }

    public function testReadingWithDescription(): void
    {
        $sequence = $this->read('$name description');

        self::assertSame('$name', $sequence->data);
        self::assertSame(5, $sequence->offset);
    }

    #[DataProvider('asciiCharsDataProvider')]
    public function testTagWithAsciiChars(string $char, int $code): void
    {
        // The "_" allowed in middle of the variable name.
        $allowed = $code === 95
            // [0-9] also allowed.
            || $code >= 48 && $code <= 57
            // [A-Z] also allowed.
            || $code >= 65 && $code <= 90
            // [a-z] also allowed.
            || $code >= 97 && $code <= 122
        ;

        $sequence = $this->read($tag = "\$prefix{$char}suffix");

        if ($allowed) {
            self::assertSame($tag, $sequence->data);
            self::assertSame(\strlen($tag), $sequence->offset);
        } else {
            self::assertSame('$prefix', $sequence->data);
            self::assertSame(7, $sequence->offset);
        }
    }

    #[DataProvider('asciiCharsDataProvider')]
    public function testTagStartsWithAsciiChars(string $char, int $code): void
    {
        // The "_" allowed at start of the variable name.
        $allowed = $code === 95
            // [A-Z] also allowed.
            || $code >= 65 && $code <= 90
            // [a-z] also allowed.
            || $code >= 97 && $code <= 122
        ;

        if (!$allowed) {
            self::expectException(InvalidVariableNameException::class);
            self::expectExceptionCode(InvalidVariableNameException::CODE_EMPTY_NAME);
        }

        $sequence = $this->read($tag = "\${$char}suffix");

        if ($allowed) {
            self::assertSame($tag, $sequence->data);
            self::assertSame(\strlen($tag), $sequence->offset);
        }
    }

    public function testWithoutName(): void
    {
        self::expectException(InvalidVariableNameException::class);
        self::expectExceptionCode(InvalidVariableNameException::CODE_EMPTY_NAME);

        $this->read('$ description');
    }

    public function testWithoutNameAndDescription(): void
    {
        self::expectException(InvalidVariableNameException::class);
        self::expectExceptionCode(InvalidVariableNameException::CODE_EMPTY_NAME);

        $this->read('$');
    }

    public function testEmptyTag(): void
    {
        self::expectException(InvalidVariableNameException::class);
        self::expectExceptionCode(InvalidVariableNameException::CODE_EMPTY);

        $this->read('');
    }

    public function testWithoutTag(): void
    {
        self::expectException(InvalidVariableNameException::class);
        self::expectExceptionCode(InvalidVariableNameException::CODE_INVALID_PREFIX);

        $this->read('description');
    }
}
