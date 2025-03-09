<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PHPDoc\DocBlock\Tag\Content\Stream;
use TypeLang\PHPDoc\DocBlock\Tag\Description\Description;
use TypeLang\PHPDoc\DocBlock\Tag\Description\TaggedDescription;
use TypeLang\PHPDoc\DocBlock\Tag\Description\TaggedDescriptionInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Parser\Description\RegexDescriptionParser;
use TypeLang\PHPDoc\Parser\Tag\RegexTagParser;

final class DescriptionParserTest extends TestCase
{
    public static function parserDataProvider(): iterable
    {
        $tags = new TagFactory([
            'error' => new class implements TagFactoryInterface {
                public function create(Stream $tag, DescriptionParserInterface $descriptions): TagInterface
                {
                    throw new \LogicException('Error tag ' . $tag->getName());
                }
            },
        ]);

        yield RegexDescriptionParser::class => [new RegexDescriptionParser(
            tags: new RegexTagParser($tags),
        )];
    }

    #[DataProvider('parserDataProvider')]
    public function testSimpleDescription(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new Description('Hello World!'),
            actual: $parser->parse('Hello World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithInlineTag(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('tag'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@tag} World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithDescribedInlineTag(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('tag', 'description'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@tag description} World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithMultipleInlineTags(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('tag1'),
                new Description(' '),
                new Tag('tag2', '#desc'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@tag1} {@tag2#desc} World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithSprintfSyntax(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new Description('Hello %s World!'),
            actual: $parser->parse('Hello %s World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithSprintfSyntaxInsideInlineTag(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('test-some', 'Desc %s 42'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@test-some Desc %s 42} World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithNonNamedTag(DescriptionParserInterface $parser): void
    {
        self::assertEquals(
            expected: new Description('Hello {@} World!'),
            actual: $parser->parse('Hello {@} World!'),
        );
    }

    #[DataProvider('parserDataProvider')]
    public function testDescriptionWithBadTagName(DescriptionParserInterface $parser): void
    {
        $description = $parser->parse('Hello {@@} World!');

        self::assertInstanceOf(TaggedDescriptionInterface::class, $description);
        self::assertCount(3, $description);
        self::assertInstanceOf(InvalidTag::class, $description[1]);

        $reason = $description[1]->reason;

        self::assertSame('Tag name cannot be empty', $reason->getMessage());
        self::assertSame(InvalidTag::DEFAULT_UNKNOWN_TAG_NAME, $description[1]->getName());
        self::assertEquals(new Description('{@}'), $description[1]->getDescription());
    }

    #[DataProvider('parserDataProvider')]
    public function testErrorWhileParsingInline(DescriptionParserInterface $parser): void
    {
        $description = $parser->parse('Hello {@error description} World!');

        self::assertInstanceOf(TaggedDescriptionInterface::class, $description);
        self::assertCount(3, $description);
        self::assertInstanceOf(InvalidTag::class, $description[1]);

        $reason = $description[1]->reason;

        self::assertSame('Error while parsing tag @error', $reason->getMessage());
        self::assertSame('error', $description[1]->getName());
        self::assertEquals(new Description('description'), $description[1]->getDescription());
    }
}
