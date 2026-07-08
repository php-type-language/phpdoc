<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanFileSuppressTag\PhanFileSuppressTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressCurrentLineTag\PhanSuppressCurrentLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextLineTag\PhanSuppressNextLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextNextLineTag\PhanSuppressNextNextLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressPreviousLineTag\PhanSuppressPreviousLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreTag\PhpStanIgnoreTag;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTag;

final class IssueListTagTest extends TagTestCase
{
    #[Test]
    public function parsesSingleIssue(): void
    {
        $tag = self::parseTag('@suppress PhanUnreferencedClass');

        self::assertInstanceOf(SuppressTag::class, $tag);
        self::assertSame('suppress', $tag->name);
        self::assertSame(['PhanUnreferencedClass'], $tag->issues);
        self::assertNull($tag->description);
        self::assertSame('@suppress PhanUnreferencedClass', (string) $tag);
    }

    #[Test]
    public function parsesIssueListAndDescription(): void
    {
        $tag = self::parseTag('@suppress PHPStan.method.notFound, psalm-var, a.b-c_d Explanation goes here.');

        self::assertInstanceOf(SuppressTag::class, $tag);
        self::assertSame(['PHPStan.method.notFound', 'psalm-var', 'a.b-c_d'], $tag->issues);
        self::assertSame('Explanation goes here.', (string) $tag->description);
        self::assertSame(
            '@suppress PHPStan.method.notFound, psalm-var, a.b-c_d Explanation goes here.',
            (string) $tag,
        );
    }

    #[Test]
    public function rejectsMissingIssue(): void
    {
        self::assertInstanceOf(InvalidTag::class, self::parseTag('@suppress'));
    }

    /**
     * @param class-string<IssueListTag> $expected
     */
    #[Test]
    #[DataProvider('issueListTagProvider')]
    public function issueListTagIsRecognized(string $name, string $expected): void
    {
        $tag = self::parseTag(\sprintf('@%s Foo.Bar, Baz', $name));

        self::assertInstanceOf($expected, $tag);
        self::assertInstanceOf(IssueListTag::class, $tag);
        self::assertSame($name, $tag->name);
        self::assertSame(['Foo.Bar', 'Baz'], $tag->issues);
    }

    /**
     * @return iterable<string, array{non-empty-string, class-string<IssueListTag>}>
     */
    public static function issueListTagProvider(): iterable
    {
        yield '@suppress' => ['suppress', SuppressTag::class];
        yield '@phpstan-ignore' => ['phpstan-ignore', PhpStanIgnoreTag::class];
        yield '@phan-file-suppress' => ['phan-file-suppress', PhanFileSuppressTag::class];
        yield '@phan-suppress-current-line' => ['phan-suppress-current-line', PhanSuppressCurrentLineTag::class];
        yield '@phan-suppress-next-line' => ['phan-suppress-next-line', PhanSuppressNextLineTag::class];
        yield '@phan-suppress-next-next-line' => ['phan-suppress-next-next-line', PhanSuppressNextNextLineTag::class];
        yield '@phan-suppress-previous-line' => ['phan-suppress-previous-line', PhanSuppressPreviousLineTag::class];
    }
}
