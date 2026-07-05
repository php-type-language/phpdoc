<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser\Grammar;

use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Combinator\DescriptionCombinator;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\LinkTag\LinkTag;
use TypeLang\PhpDoc\DocBlock\Tag\LinkTag\LinkTagDefinition;
use TypeLang\PhpDoc\Exception\MalformedTagException;
use TypeLang\PhpDoc\Parser\Description\BalancedBraceAwareParser;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\Grammar\Grammar;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\TagFactory;
use TypeLang\PhpDoc\Tests\TestCase;

final class DefinitionTest extends TestCase
{
    #[Test]
    public function ruleStringifiesToItsGrammar(): void
    {
        self::assertSame('<URI> [ <Description> ]', (string) new LinkTagDefinition()->spec);
    }

    #[Test]
    public function matchesUriAndDescription(): void
    {
        $tag = self::factory()->create(
            'link',
            'https://example.com Some description',
        );

        self::assertInstanceOf(LinkTag::class, $tag);
        self::assertSame('link', $tag->name);
        self::assertSame('https://example.com', (string) $tag->reference);
        self::assertInstanceOf(DescriptionInterface::class, $tag->description);
        self::assertSame('Some description', (string) $tag->description);
    }

    #[Test]
    public function matchesUriWithoutDescription(): void
    {
        $tag = self::factory()->create('link', 'https://example.com');

        self::assertInstanceOf(LinkTag::class, $tag);
        self::assertSame('https://example.com', (string) $tag->reference);
        self::assertNull($tag->description);
    }

    /**
     * The description delegate resolves nested inline tags, so a `{@...}` in the
     * description becomes a {@see TaggedDescription}.
     */
    #[Test]
    public function descriptionKeepsInlineTags(): void
    {
        $tag = self::factory()->create(
            'link',
            'https://example.com see {@link X}',
        );

        self::assertInstanceOf(LinkTag::class, $tag);
        self::assertInstanceOf(TaggedDescription::class, $tag->description);
        self::assertSame('see {@link X}', (string) $tag->description);
    }

    #[Test]
    public function missingRequiredUriIsMalformed(): void
    {
        $tag = self::factory()->create('link', '');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
        self::assertSame(
            'Malformed "@link" tag, expected: <URI> [ <Description> ]',
            $tag->reason->getMessage(),
        );
    }

    /**
     * The reported offset points at where the tag body failed to match.
     */
    #[Test]
    public function malformedTagReportsFailureOffset(): void
    {
        $tag = self::factory()->create('link', '     ');

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
        self::assertSame(5, $tag->reason->offset);
        self::assertSame('     ', $tag->reason->source);
    }

    /**
     * A tag with no registered definition falls back to a plain tag whose whole
     * suffix becomes the description.
     */
    #[Test]
    public function unregisteredTagFallsBackToPlainTag(): void
    {
        $tag = self::factory()->create('unknown', 'free text');

        self::assertNotInstanceOf(LinkTag::class, $tag);
        self::assertSame('unknown', $tag->name);
        self::assertSame('free text', (string) $tag->description);
    }

    /**
     * A suffix with no URI produces a malformed tag rather than an error.
     */
    #[Test]
    public function uriReaderSoftFailurePropagatesAsMalformedTag(): void
    {
        $tag = self::factory()->create('link', "\t");

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(MalformedTagException::class, $tag->reason);
    }

    private static function factory(): TagFactory
    {
        return new TagFactory(
            definitions: ['link' => new LinkTagDefinition()],
            combinators: self::grammar(),
        );
    }

    /**
     * A grammar with the two terminals a `@link` tag references: `URI`, which
     * reads a single whitespace-delimited word, and `description`, which
     * consumes the trailing text.
     */
    private static function grammar(): Grammar
    {
        $grammar = new Grammar();

        $grammar->add(UriCombinator::NAME, new UriCombinator());
        $grammar->add(DescriptionCombinator::NAME, new DescriptionCombinator(self::descriptions()));

        return $grammar;
    }

    private static function descriptions(): DescriptionParserInterface
    {
        return new BalancedBraceAwareParser(new StringTagParser(self::createTagFactory()));
    }
}
