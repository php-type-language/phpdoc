<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UrlCombinator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\UrlValidatorInterface;
use TypeLang\PhpDoc\Tests\TestCase;

abstract class UrlValidatorTestCase extends TestCase
{
    abstract protected function createValidator(): UrlValidatorInterface;

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function validDataProvider(): iterable
    {
        yield 'host' => ['https://example.com'];
        yield 'path, query and fragment' => ['https://example.com/path?q=1#x'];
        yield 'upper case' => ['HTTPS://EXAMPLE.COM'];
        yield 'host beyond ASCII' => ['https://пример.рф/путь'];
        yield 'IPv6 host and port' => ['http://[::1]:8080/x'];
        yield 'file with a host' => ['file://server/share'];
    }

    #[Test]
    #[DataProvider('validDataProvider')]
    public function acceptsAUrl(string $url): void
    {
        self::assertTrue($this->createValidator()->isValid($url));
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'credentials' => ['https://user:pass@example.com/x'];
        yield 'empty host' => ['file:///etc/hosts'];
        yield 'no host' => ['https://'];
        yield 'no authority' => ['http:example.com'];
        yield 'backslashes' => ['https:\\\\example.com'];
        yield 'mailto' => ['mailto:user@example.com'];
        yield 'urn' => ['urn:isbn:123'];
        yield 'code reference' => ['Foo::bar()'];
        yield 'relative path' => ['docs/index.html'];
        yield 'invalid scheme' => ['ht!tp://x'];
        yield 'scheme with a digit first' => ['1http://x'];
        yield 'unclosed IPv6 host' => ['http://[bad'];
        yield 'forbidden character in the host' => ['https://exa<mple.com'];
        yield 'port of letters' => ['https://example.com:abc'];
        yield 'port out of range' => ['https://example.com:99999'];
        yield 'braces in the path' => ['https://example.com/a{b}'];
        yield 'malformed percent in the path' => ['https://example.com/%zz'];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function rejectsAnInvalidUrl(string $url): void
    {
        self::assertFalse($this->createValidator()->isValid($url));
    }
}
