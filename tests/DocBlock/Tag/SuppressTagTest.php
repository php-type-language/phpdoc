<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\IssueNameCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
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
