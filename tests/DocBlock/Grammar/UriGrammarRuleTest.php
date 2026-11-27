<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Grammar;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

final class UriGrammarRuleTest extends GrammarRuleTestCase
{
    protected function rule(): UriCombinator
    {
        return new UriCombinator();
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function validDataProvider(): iterable
    {
        yield 'absolute' => ['https://example.com/path'];
        yield 'relative' => ['docs/index.html'];
    }

    #[Test]
    #[DataProvider('validDataProvider')]
    public function readsAUri(string $input): void
    {
        $reference = $this->matchText($input);

        self::assertInstanceOf(UriReference::class, $reference);
        self::assertSame($input, (string) $reference);
    }

    #[Test]
    public function stopsAtTheFirstWhitespace(): void
    {
        $cursor = new Cursor('docs/index.html and the rest');

        self::assertSame('docs/index.html', (string) $this->matchCursor($cursor));
        self::assertSame(15, $cursor->getOffset());
    }

    /**
     * @return iterable<non-empty-string, array{string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'slashes only' => ['//'];
        yield 'delimiters only' => ['/?#'];
        yield 'not a URI' => ['<b>x</b>'];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function rejectsAnInvalidUri(string $input): void
    {
        $this->expectException(NoMatchException::class);

        $this->matchText($input);
    }
}
