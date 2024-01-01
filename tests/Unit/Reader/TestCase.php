<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Tests\Unit\Reader;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\ReaderInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\Sequence;
use TypeLang\PhpDoc\Parser\Tests\TestCase as BaseTestCase;

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
