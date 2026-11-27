<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UriCombinator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\UriValidatorInterface;
use TypeLang\PhpDoc\Tests\TestCase;

abstract class UriValidatorTestCase extends TestCase
{
    abstract protected function createValidator(): UriValidatorInterface;

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function validDataProvider(): iterable
    {
        yield 'absolute' => ['https://example.com/path?q=1#x'];
        yield 'upper case' => ['HTTPS://EXAMPLE.COM'];
        yield 'credentials and port' => ['https://user:pass@example.com:8080/x'];
        yield 'IPv6 host' => ['http://[::1]:8080/x'];
        yield 'empty host' => ['file:///etc/hosts'];
        yield 'no authority' => ['http:example.com'];
        yield 'mailto' => ['mailto:user@example.com'];
        yield 'urn' => ['urn:isbn:123'];
        yield 'short scheme' => ['a:b'];
        yield 'code reference' => ['Foo::bar()'];
        yield 'relative path' => ['docs/index.html'];
        yield 'colon after the first segment' => ['/docs/a:b'];
        yield 'query' => ['?q=1'];
        yield 'fragment' => ['#anchor'];
        yield 'percent-encoded' => ['docs/a%20b.html'];
    }

    #[Test]
    #[DataProvider('validDataProvider')]
    public function acceptsAUri(string $uri): void
    {
        self::assertTrue($this->createValidator()->isValid($uri));
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'host beyond ASCII' => ['https://пример.рф/путь'];
        yield 'path beyond ASCII' => ['docs/путь.html'];
        yield 'invalid scheme' => ['ht!tp://x'];
        yield 'scheme with a digit first' => ['1http://x'];
        yield 'unclosed IPv6 host' => ['http://[bad'];
        yield 'forbidden character in the host' => ['https://exa<mple.com'];
        yield 'port of letters' => ['https://example.com:abc'];
        yield 'brackets in the path' => ['docs/a[1]'];
        yield 'braces in the path' => ['https://example.com/a{b}'];
        yield 'backslashes' => ['https:\\\\example.com'];
        yield 'markup' => ['<b>x</b>'];
        yield 'braces' => ['{braces}'];
        yield 'malformed percent' => ['%zz'];
        yield 'malformed percent in the path' => ['https://example.com/%zz'];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function rejectsAnInvalidUri(string $uri): void
    {
        self::assertFalse($this->createValidator()->isValid($uri));
    }
}
