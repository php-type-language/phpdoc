<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PhpDocParser\DocBlock\Reader\ReaderInterface;
use TypeLang\PhpDocParser\DocBlock\Reader\Sequence;
use TypeLang\PhpDocParser\Tests\TestCase as BaseTestCase;

#[Group('unit'), Group('type-lang/phpdoc-parser')]
abstract class TestCase extends BaseTestCase
{
    abstract protected function getReader(): ReaderInterface;

    protected function read(string $content): ?Sequence
    {
        $reader = $this->getReader();

        return $reader->read($content);
    }

    /**
     * @return array{non-empty-string, string}
     */
    protected function pair(string $content): array
    {
        $sequence = $this->read($content);

        self::assertNotNull($sequence);

        return [$sequence->data, \substr($content, $sequence->offset)];
    }

    public static function asciiCharsDataProvider(): array
    {
        $result = [];

        for ($i = 0; $i < 128; ++$i) {
            $char = \chr($i);
            $name = "chr($i)" . (\ctype_print($char) ? ' ' . $char : '');

            $result[$name] = [$char, $i];
        }

        return $result;
    }
}
