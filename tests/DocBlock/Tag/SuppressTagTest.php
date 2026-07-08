<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanFileSuppressTag\PhanFileSuppressTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressCurrentLineTag\PhanSuppressCurrentLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextLineTag\PhanSuppressNextLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextNextLineTag\PhanSuppressNextNextLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressPreviousLineTag\PhanSuppressPreviousLineTag;
use TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreTag\PhpStanIgnoreTag;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTag;
use TypeLang\PhpDoc\DocBlock\Tag\SuppressTag\SuppressTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\TagFactory;
use TypeLang\PhpDoc\Parser\TagRegistry;
use TypeLang\PhpDoc\Tests\TestCase;

final class SuppressTagTest extends TestCase
{
    #[Test]
    public function parsesSingleIssue(): void
    {
        $tag = self::factory()->create('suppress', 'PhanUnreferencedClass');

        self::assertInstanceOf(SuppressTag::class, $tag);
        self::assertSame(['PhanUnreferencedClass'], $tag->issues);
        self::assertNull($tag->description);
        self::assertSame('@suppress PhanUnreferencedClass', (string) $tag);
    }

    #[Test]
    public function parsesIssueListAndDescription(): void
    {
        $tag = self::factory()->create('suppress', 'PHPStan.method.notFound, psalm-var, a.b-c_d Explanation goes here.');

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
        $tag = self::factory()->create('suppress', '');

        self::assertInstanceOf(InvalidTag::class, $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @suppress PhanTypeMismatch, PhanUnusedVariable */');

        self::assertInstanceOf(SuppressTag::class, $block->tags[0]);
        self::assertSame(['PhanTypeMismatch', 'PhanUnusedVariable'], $block->tags[0]->issues);
    }

    /**
     * @return iterable<string, array{string, class-string<IssueListTag>}>
     */
    public static function tagProvider(): iterable
    {
        yield '@suppress' => ['suppress', SuppressTag::class];
        yield '@phpstan-ignore' => ['phpstan-ignore', PhpStanIgnoreTag::class];
        yield '@phan-file-suppress' => ['phan-file-suppress', PhanFileSuppressTag::class];
        yield '@phan-suppress-current-line' => ['phan-suppress-current-line', PhanSuppressCurrentLineTag::class];
        yield '@phan-suppress-next-line' => ['phan-suppress-next-line', PhanSuppressNextLineTag::class];
        yield '@phan-suppress-next-next-line' => ['phan-suppress-next-next-line', PhanSuppressNextNextLineTag::class];
        yield '@phan-suppress-previous-line' => ['phan-suppress-previous-line', PhanSuppressPreviousLineTag::class];
    }

    /**
     * @param class-string<IssueListTag> $expected
     */
    #[Test]
    #[DataProvider('tagProvider')]
    public function issueListTagResolvesThroughTheRealParser(string $name, string $expected): void
    {
        $block = new DocBlockParser()->parse(\sprintf('/** @%s Foo.Bar, Baz */', $name));

        self::assertCount(1, $block->tags);
        self::assertInstanceOf($expected, $block->tags[0]);
        self::assertInstanceOf(IssueListTag::class, $block->tags[0]);
        self::assertSame($name, $block->tags[0]->name);
        self::assertSame(['Foo.Bar', 'Baz'], $block->tags[0]->issues);
    }

    private static function factory(): TagFactory
    {
        $registry = new TagRegistry([
            SuppressTagDefinition::NAME => new SuppressTagDefinition(),
        ]);

        return new TagFactory($registry, [
            IssueNameCombinator::NAME => new IssueNameCombinator(),
            DescriptionCombinator::NAME => new DescriptionCombinator(self::createDescriptionParser()),
        ]);
    }
}
