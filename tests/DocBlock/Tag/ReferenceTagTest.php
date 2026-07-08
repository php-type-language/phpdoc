<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Reference\ClassMethodReference;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\LinkTag\LinkTag;
use TypeLang\PhpDoc\DocBlock\Tag\ReferencedTagInterface;
use TypeLang\PhpDoc\DocBlock\Tag\SeeTag\SeeTag;
use TypeLang\PhpDoc\DocBlock\Tag\UsedByTag\UsedByTag;
use TypeLang\PhpDoc\DocBlock\Tag\UsesTag\UsesTag;

final class ReferenceTagTest extends TagTestCase
{
    #[Test]
    public function parsesCodeReferenceWithDescription(): void
    {
        $tag = self::parseTag('@uses Mailer::send() The underlying transport.');

        self::assertInstanceOf(UsesTag::class, $tag);
        self::assertInstanceOf(ReferencedTagInterface::class, $tag);
        self::assertSame('uses', $tag->name);
        self::assertInstanceOf(ClassMethodReference::class, $tag->reference);
        self::assertSame('The underlying transport.', (string) $tag->description);
        self::assertSame('@uses Mailer::send() The underlying transport.', (string) $tag);
    }

    #[Test]
    public function parsesCodeReferenceWithoutDescription(): void
    {
        $tag = self::parseTag('@used-by Service::run()');

        self::assertInstanceOf(UsedByTag::class, $tag);
        self::assertSame('used-by', $tag->name);
        self::assertInstanceOf(ClassMethodReference::class, $tag->reference);
        self::assertNull($tag->description);
        self::assertSame('@used-by Service::run()', (string) $tag);
    }

    #[Test]
    public function usesRejectsUri(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@uses https://example.com'));
    }

    #[Test]
    public function linkParsesUriAndDescription(): void
    {
        $tag = self::parseTag('@link https://example.com Project homepage.');

        self::assertInstanceOf(LinkTag::class, $tag);
        self::assertSame('link', $tag->name);
        self::assertInstanceOf(UriReference::class, $tag->reference);
        self::assertSame('https://example.com', (string) $tag->reference);
        self::assertSame('Project homepage.', (string) $tag->description);
        self::assertSame('@link https://example.com Project homepage.', (string) $tag);
    }

    #[Test]
    public function seeAcceptsBothCodeReferenceAndUri(): void
    {
        $reference = self::parseTag('@see Mailer::send()');
        self::assertInstanceOf(SeeTag::class, $reference);
        self::assertInstanceOf(ClassMethodReference::class, $reference->reference);

        $uri = self::parseTag('@see https://example.com The manual.');
        self::assertInstanceOf(SeeTag::class, $uri);
        self::assertInstanceOf(UriReference::class, $uri->reference);
        self::assertSame('The manual.', (string) $uri->description);
    }

    /**
     * @param class-string<ReferencedTagInterface> $expected
     */
    #[Test]
    #[DataProvider('codeReferenceTagProvider')]
    public function codeReferenceTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s Service::run()', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(ReferencedTagInterface::class, $tag);
        self::assertSame($name, $tag->name);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<ReferencedTagInterface>}>
     */
    public static function codeReferenceTagProvider(): iterable
    {
        yield '@uses' => ['uses', UsesTag::class];
        yield '@used-by' => ['used-by', UsedByTag::class];
        yield '@see' => ['see', SeeTag::class];
    }
}
