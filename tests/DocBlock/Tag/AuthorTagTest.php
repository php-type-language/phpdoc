<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Tag;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\AuthorNameCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\EmailCombinator;
use TypeLang\PhpDoc\DocBlock\Tag\AuthorTag\AuthorTag;
use TypeLang\PhpDoc\DocBlock\Tag\AuthorTag\AuthorTagDefinition;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class AuthorTagTest extends TestCase
{
    #[Test]
    public function parsesNameAndEmail(): void
    {
        $tag = self::factory()->create('author', 'John Doe <john@example.com>');

        self::assertInstanceOf(AuthorTag::class, $tag);
        self::assertSame('author', $tag->name);
        self::assertSame('John Doe', $tag->author);
        self::assertSame('john@example.com', $tag->email);
        self::assertSame('@author John Doe <john@example.com>', (string) $tag);
    }

    #[Test]
    public function parsesNameWithoutEmail(): void
    {
        $tag = self::factory()->create('author', 'Jane Roe');

        self::assertInstanceOf(AuthorTag::class, $tag);
        self::assertSame('Jane Roe', $tag->author);
        self::assertNull($tag->email);
        self::assertSame('@author Jane Roe', (string) $tag);
    }

    #[Test]
    public function resolvesThroughTheRealParser(): void
    {
        $block = new DocBlockParser()->parse('/** @author Kirill <k@example.com> */');

        self::assertCount(1, $block->tags);
        self::assertInstanceOf(AuthorTag::class, $block->tags[0]);
        self::assertSame('k@example.com', $block->tags[0]->email);
    }

    private static function factory(): TagFactory
    {
        $factory = new \ReflectionClass(TagFactory::class)
            ->newLazyProxy(function () use (&$factory) {
                return new TagFactory(
                    definitions: [
                        AuthorTagDefinition::NAME => new AuthorTagDefinition(),
                    ],
                    combinators: [
                        AuthorNameCombinator::NAME => new AuthorNameCombinator(),
                        EmailCombinator::NAME => new EmailCombinator(),
                        DescriptionCombinator::NAME => new DescriptionCombinator(
                            new BalancedBraceAwareParser(
                                new StringTagParser($factory)
                            ),
                        ),
                    ],
                );
            });

        return $factory;
    }
}
