<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Grammar;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;
use TypeLang\PhpDoc\DocBlock\Reference\UrlReference;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

final class UrlGrammarRuleTest extends GrammarRuleTestCase
{
    protected function rule(): UrlCombinator
    {
        return new UrlCombinator();
    }

    #[Test]
    public function readsAUrl(): void
    {
        $reference = $this->matchText('https://example.com/path');

        self::assertInstanceOf(UrlReference::class, $reference);
        self::assertSame('https://example.com/path', (string) $reference);
    }

    #[Test]
    public function stopsAtTheFirstWhitespace(): void
    {
        $cursor = new Cursor('https://example.com and the rest');

        self::assertSame('https://example.com', (string) $this->matchCursor($cursor));
        self::assertSame(19, $cursor->getOffset());
    }

    /**
     * @return iterable<non-empty-string, array{string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'not a URL' => ['docs/index.html'];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function rejectsAnInvalidUrl(string $input): void
    {
        $this->expectException(NoMatchException::class);

        $this->matchText($input);
    }
}
