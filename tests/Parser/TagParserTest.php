<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\InvalidTag;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\EmptyTagLineException;
use TypeLang\PhpDoc\Exception\EmptyTagNameException;
use TypeLang\PhpDoc\Exception\InvalidTagNameException;
use TypeLang\PhpDoc\Exception\InvalidTagPrefixException;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Parser\Tag\StringTagParser;
use TypeLang\PhpDoc\Parser\Tag\TagParserInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class TagParserTest extends TestCase
{
    /**
     * @return iterable<string, array{TagParserInterface}>
     */
    public static function parserDataProvider(): iterable
    {
        yield 'StringTagParser' => [
            new StringTagParser(self::createTagFactory()),
        ];
    }

    /**
     * @return iterable<string, array{TagParserInterface, string, string, string|null}>
     */
    public static function validTagDataProvider(): iterable
    {
        $cases = [
            'name only, no description' => ['@see', 'see', null],
            'name followed by a description' => ['@param int $x foo', 'param', 'int $x foo'],
            // Only the leading whitespace of the suffix is stripped.
            'leading whitespace of the suffix is trimmed' => ['@see    Foo::bar()', 'see', 'Foo::bar()'],
            'hyphenated vendor name' => ['@psalm-param string $s', 'psalm-param', 'string $s'],
            'colon inside the name' => ['@foo:bar baz', 'foo:bar', 'baz'],
            'fully qualified name with backslashes' => ['@\\Vendor\\Attribute value', '\\Vendor\\Attribute', 'value'],
            // Word characters, hyphens, underscores, backslashes and colons all
            // form a name, and any of them may be the first character.
            'digit as the first name character' => ['@123 desc', '123', 'desc'],
            'hyphen as the first name character' => ['@-x rest', '-x', 'rest'],
            'digits after the first name character' => ['@v1 desc', 'v1', 'desc'],
            // The name ends at the first character that is not part of a name.
            'name stops at the first non-name character' => ['@see, other', 'see', ', other'],
        ];

        return self::bind($cases);
    }

    /**
     * @return iterable<string, array{TagParserInterface, string, class-string<\Throwable>, string, string|null}>
     */
    public static function invalidTagDataProvider(): iterable
    {
        $cases = [
            'empty definition' => ['', EmptyTagLineException::class, '', null],
            'missing "@" prefix' => ['foo bar', InvalidTagPrefixException::class, '', 'foo bar'],
            '"@" followed by whitespace' => ['@ foo', EmptyTagNameException::class, '', ' foo'],
            'bare "@"' => ['@', EmptyTagNameException::class, '', null],
            // Punctuation such as "!" is not a name character.
            '"@" followed by punctuation' => ['@!bad', EmptyTagNameException::class, '', '!bad'],
        ];

        return self::bind($cases);
    }

    /**
     * @return iterable<string, array{TagParserInterface, string}>
     */
    public static function malformedDefinitionDataProvider(): iterable
    {
        $definitions = [
            'empty definition' => '',
            'missing "@" prefix' => 'foo bar',
            '"@" followed by whitespace' => '@ foo',
            'bare "@"' => '@',
            '"@" followed by punctuation' => '@!bad',
        ];

        return self::bind(\array_map(static fn(string $input): array => [$input], $definitions));
    }

    /**
     * @return iterable<string, array{TagParserInterface, string}>
     */
    public static function roundTripDataProvider(): iterable
    {
        $inputs = [
            'name only' => '@see',
            'name and description' => '@param int $x foo',
            'hyphenated name' => '@psalm-param string $s',
            'colon in name' => '@foo:bar baz',
            'qualified name' => '@\\Vendor\\Attribute value',
            'digit-led name' => '@123 desc',
            'digits in name' => '@v1 desc',
            'unicode name' => '@ключ значение',
            'inline tag in description' => '@see {@link X}',
        ];

        return self::bind(\array_map(static fn(string $input): array => [$input], $inputs));
    }

    #[Test]
    #[DataProvider('validTagDataProvider')]
    public function validDefinitionBecomesTagWithParsedNameAndDescription(
        TagParserInterface $parser,
        string $definition,
        string $expectedName,
        ?string $expectedDescription,
    ): void {
        $tag = $parser->parse($definition, self::descriptions());

        self::assertNotInstanceOf(InvalidTag::class, $tag);
        self::assertSame($expectedName, $tag->name);
        self::assertTagDescription($expectedDescription, $tag);
    }

    #[Test]
    #[DataProvider('invalidTagDataProvider')]
    public function malformedDefinitionBecomesInvalidTagWithReason(
        TagParserInterface $parser,
        string $definition,
        string $expectedReason,
        string $expectedName,
        ?string $expectedDescription,
    ): void {
        $tag = $parser->parse($definition, self::descriptions());

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf($expectedReason, $tag->reason);
        self::assertSame($expectedName, $tag->name);
        self::assertTagDescription($expectedDescription, $tag);
    }

    #[Test]
    #[DataProvider('malformedDefinitionDataProvider')]
    public function invalidTagReasonIsAnInvalidTagNameException(
        TagParserInterface $parser,
        string $definition,
    ): void {
        $tag = $parser->parse($definition, self::descriptions());

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(InvalidTagNameException::class, $tag->reason);
        self::assertInstanceOf(ParsingExceptionInterface::class, $tag->reason);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function parseReportsFailuresWithoutThrowing(TagParserInterface $parser): void
    {
        foreach (['', '@', '@ foo', '@!bad', 'no-at-sign'] as $definition) {
            self::assertInstanceOf(TagInterface::class, $parser->parse($definition, self::descriptions()));
        }
    }

    #[Test]
    #[DataProvider('roundTripDataProvider')]
    public function stringCastReproducesDefinition(TagParserInterface $parser, string $definition): void
    {
        self::assertSame($definition, (string) $parser->parse($definition, self::descriptions()));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function suffixIsParsedByTheInjectedDescriptionParser(TagParserInterface $parser): void
    {
        $tag = $parser->parse('@see {@link X}', self::descriptions());

        self::assertSame('see', $tag->name);
        self::assertInstanceOf(TaggedDescription::class, $tag->description);
        self::assertCount(1, $tag->description->tags);
        self::assertSame('link', $tag->description->tags[0]->name);
        self::assertSame('{@link X}', (string) $tag->description);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function definitionWithoutPrefixKeepsWholeTextAsDescription(TagParserInterface $parser): void
    {
        $tag = $parser->parse('just some text', self::descriptions());

        self::assertInstanceOf(InvalidTag::class, $tag);
        self::assertInstanceOf(InvalidTagPrefixException::class, $tag->reason);
        self::assertTagDescription('just some text', $tag);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function unicodeTagNameIsSupported(TagParserInterface $parser): void
    {
        $tag = $parser->parse('@ключ значение', self::descriptions());

        self::assertNotInstanceOf(InvalidTag::class, $tag);
        self::assertSame('ключ', $tag->name);
        self::assertTagDescription('значение', $tag);
    }

    private static function descriptions(): DescriptionParserInterface
    {
        return self::createDescriptionParser();
    }

    /**
     * @param iterable<string, list<mixed>> $cases
     * @return iterable<string, list<mixed>>
     */
    private static function bind(iterable $cases): iterable
    {
        foreach (self::parserDataProvider() as $parserName => [$parser]) {
            foreach ($cases as $caseName => $case) {
                yield $parserName . ': ' . $caseName => [$parser, ...$case];
            }
        }
    }

    private static function assertTagDescription(?string $expected, TagInterface $tag): void
    {
        if ($expected === null) {
            self::assertNull($tag->description);

            return;
        }

        self::assertInstanceOf(DescriptionInterface::class, $tag->description);
        self::assertSame($expected, (string) $tag->description);
    }
}
