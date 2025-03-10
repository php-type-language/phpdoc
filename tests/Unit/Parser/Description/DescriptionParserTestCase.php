<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Unit\Parser\Description;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PHPDoc\DocBlock\Description\Description;
use TypeLang\PHPDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PHPDoc\DocBlock\Description\TaggedDescriptionInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PHPDoc\DocBlock\Tag\InvalidTagInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Parser\Tag\RegexTagParser;
use TypeLang\PHPDoc\Parser\Tag\TagParserInterface;
use TypeLang\PHPDoc\Tests\Unit\Parser\ParserTestCase;

abstract class DescriptionParserTestCase extends ParserTestCase
{
    abstract public static function getDescriptionParser(TagParserInterface $tags): DescriptionParserInterface;

    protected static function getParser(): DescriptionParserInterface
    {
        $tags = new TagFactory([
            'error' => new class implements TagFactoryInterface {
                public function create(
                    string $tag,
                    string $content,
                    DescriptionParserInterface $descriptions,
                ): TagInterface {
                    throw new \LogicException('Error tag ' . $tag);
                }
            },
        ]);

        return static::getDescriptionParser(
            tags: new RegexTagParser($tags),
        );
    }

    public function testSimpleDescription(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new Description('Hello World!'),
            actual: $parser->parse('Hello World!'),
        );
    }

    public function testDescriptionWithInlineTag(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('tag'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@tag} World!'),
        );
    }

    public function testDescriptionWithDescribedInlineTag(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('tag', 'description'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@tag description} World!'),
        );
    }

    public function testDescriptionWithMultipleInlineTags(): void
    {
        $parser = static::getParser();

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

    public function testDescriptionWithSprintfSyntax(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new Description('Hello %s World!'),
            actual: $parser->parse('Hello %s World!'),
        );
    }

    public function testDescriptionWithSprintfSyntaxInsideInlineTag(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new TaggedDescription([
                new Description('Hello '),
                new Tag('test-some', 'Desc %s 42'),
                new Description(' World!'),
            ]),
            actual: $parser->parse('Hello {@test-some Desc %s 42} World!'),
        );
    }

    public function testDescriptionWithNonNamedTag(): void
    {
        $parser = static::getParser();

        self::assertEquals(
            expected: new Description('Hello {@} World!'),
            actual: $parser->parse('Hello {@} World!'),
        );
    }

    public function testDescriptionWithBadTagName(): void
    {
        $parser = static::getParser();

        $description = $parser->parse('Hello {@@} World!');

        self::assertInstanceOf(TaggedDescriptionInterface::class, $description);
        self::assertCount(3, $description->components);
        self::assertCount(1, $description);
        self::assertInstanceOf(InvalidTagInterface::class, $description[0]);

        $reason = $description[0]->reason;

        self::assertSame('Tag name cannot be empty', $reason->getMessage());
        self::assertSame('unknown', $description[0]->name);
        self::assertEquals(new Description('@@'), $description[0]->description);
    }

    public function testErrorWhileParsingInline(): void
    {
        $parser = static::getParser();

        $description = $parser->parse('Hello {@error description} World!');

        self::assertInstanceOf(TaggedDescriptionInterface::class, $description);
        self::assertCount(3, $description->components);
        self::assertCount(1, $description);
        self::assertInstanceOf(InvalidTag::class, $description[0]);

        $reason = $description[0]->reason;

        self::assertSame('Error while parsing tag @error', $reason->getMessage());
        self::assertSame('error', $description[0]->name);
        self::assertEquals(new Description('description'), $description[0]->description);
    }
}
