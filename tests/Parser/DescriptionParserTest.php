<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TypeLang\PhpDoc\DocBlock\ComponentInterface;
use TypeLang\PhpDoc\DocBlock\Description\Description;
use TypeLang\PhpDoc\DocBlock\Description\TaggedDescription;
use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PhpDoc\Tests\TestCase;

final class DescriptionParserTest extends TestCase
{
    /**
     * @return iterable<string, array{DescriptionParserInterface}>
     */
    public static function parserDataProvider(): iterable
    {
        yield 'BalancedBraceAwareParser' => [
            self::createDescriptionParser(),
        ];
    }

    /**
     * @return iterable<string, array{DescriptionParserInterface, string}>
     */
    public static function plainDescriptionDataProvider(): iterable
    {
        $inputs = [
            'plain text' => 'Hello world',
            'whitespace only' => '   ',
            // A bare "@" not wrapped in `{...}` is plain text, not a tag.
            'bare at-sign without braces' => 'Text @param int $x',
            'literal at-sign in text' => 'contact me@example.com now',
            'braces without at-sign' => 'array{int, string}',
            'empty inline tag' => '{@}',
            'text merged around empty inline tag' => 'a{@}b',
            // Unreadable tag name: the whole "{@...}" stays raw text.
            'malformed inline tag name' => '{@ foo}',
            // Unbalanced "{@..." consumes the rest of the string as text.
            'unclosed inline tag' => '{@see Foo',
        ];

        return self::withParser($inputs);
    }

    /**
     * @return iterable<string, array{DescriptionParserInterface, string}>
     */
    public static function roundTripInputsDataProvider(): iterable
    {
        $inputs = [
            'plain text' => 'Hello world',
            'empty string' => '',
            'whitespace only' => '   ',
            'braces without at-sign' => 'array{int, string}',
            'single inline tag' => '{@see Foo::bar()}',
            'inline tag with nested braces' => 'Hello {@see array{int} example} world',
            'inline tag with surrounding text' => 'See also {@see Foo::bar()} for details.',
            'adjacent inline tags' => '{@see A}{@link B}',
            'empty inline tag' => '{@}',
            'nested inline tags' => '{@see {@link X}}',
            'unclosed inline tag' => '{@see Foo',
        ];

        return self::withParser($inputs);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function tryParseReturnsNullForEmptyString(DescriptionParserInterface $parser): void
    {
        self::assertNull($parser->tryParse(''));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function tryParseDelegatesToParseForNonEmptyInput(DescriptionParserInterface $parser): void
    {
        self::assertPlainDescription('Hello world', $parser->tryParse('Hello world'));
        self::assertInstanceOf(TaggedDescription::class, $parser->tryParse('{@see Foo}'));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function parseReturnsEmptyDescriptionForEmptyString(DescriptionParserInterface $parser): void
    {
        self::assertPlainDescription('', $parser->parse(''));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function parseResultIsStringable(DescriptionParserInterface $parser): void
    {
        self::assertInstanceOf(\Stringable::class, $parser->parse('plain'));
        self::assertInstanceOf(\Stringable::class, $parser->parse('{@see Foo}'));
    }

    #[Test]
    #[DataProvider('roundTripInputsDataProvider')]
    public function stringCastReproducesOriginalInput(DescriptionParserInterface $parser, string $input): void
    {
        self::assertSame($input, (string) $parser->parse($input));
    }

    #[Test]
    #[DataProvider('plainDescriptionDataProvider')]
    public function inputWithoutRecognizedTagsBecomesPlainDescription(
        DescriptionParserInterface $parser,
        string $input,
    ): void {
        self::assertPlainDescription($input, $parser->parse($input));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function singleInlineTagBecomesTaggedDescriptionWithOneTag(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('{@see Foo::bar()}');

        self::assertInstanceOf(TaggedDescription::class, $result);
        self::assertCount(1, $result);
        self::assertCount(1, $result->tags);
        self::assertTagComponent('see', 'Foo::bar()', $result->components[0]);
        self::assertSame('{@see Foo::bar()}', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function inlineTagSurroundedByTextKeepsComponentsInOrder(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('See also {@see Foo::bar()} for details.');

        self::assertInstanceOf(TaggedDescription::class, $result);
        // Text before + tag + text after == three ordered components.
        self::assertCount(3, $result);

        [$before, $tag, $after] = $result->components;

        self::assertPlainDescription('See also ', $before);
        self::assertTagComponent('see', 'Foo::bar()', $tag);
        self::assertPlainDescription(' for details.', $after);

        self::assertSame('See also {@see Foo::bar()} for details.', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function adjacentInlineTagsBecomeSeparateTags(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('{@see A}{@link B}');

        self::assertInstanceOf(TaggedDescription::class, $result);

        $names = \array_map(static fn(TagInterface $tag): string => $tag->name, $result->tags);

        self::assertSame(['see', 'link'], $names);
        self::assertSame('{@see A}{@link B}', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function nestedBracesInsideInlineTagAreRecognized(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('Hello {@see array{int} example} world');

        self::assertInstanceOf(TaggedDescription::class, $result);
        self::assertCount(3, $result);

        [$before, $tag, $after] = $result->components;

        self::assertPlainDescription('Hello ', $before);
        self::assertTagComponent('see', 'array{int} example', $tag);
        self::assertPlainDescription(' world', $after);

        self::assertSame('Hello {@see array{int} example} world', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function bareAtSignWithoutBracesIsPlainText(DescriptionParserInterface $parser): void
    {
        self::assertPlainDescription('@param int $x description', $parser->parse('@param int $x description'));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function tagWithoutDescriptionHasNullDescription(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('{@see}');

        self::assertInstanceOf(TaggedDescription::class, $result);
        self::assertCount(1, $result->tags);
        self::assertTagComponent('see', null, $result->components[0]);
        self::assertSame('{@see}', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function nestedInlineTagsAreParsedRecursively(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('{@see {@link X}}');

        self::assertInstanceOf(TaggedDescription::class, $result);
        self::assertCount(1, $result->tags);

        $outer = $result->components[0];
        self::assertInstanceOf(TagInterface::class, $outer);
        self::assertSame('see', $outer->name);

        // The nested `{@link X}` is itself parsed as the outer tag description.
        $inner = $outer->description;
        self::assertInstanceOf(TaggedDescription::class, $inner);
        self::assertCount(1, $inner->tags);
        self::assertTagComponent('link', 'X', $inner->components[0]);

        self::assertSame('{@see {@link X}}', (string) $result);
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function inlineTagWithUnreadableNameBecomesPlainDescription(DescriptionParserInterface $parser): void
    {
        self::assertPlainDescription('Hello {@!bad} world', $parser->parse('Hello {@!bad} world'));
    }

    #[Test]
    #[DataProvider('parserDataProvider')]
    public function unclosedInlineTagAfterValidTagKeepsEarlierTags(DescriptionParserInterface $parser): void
    {
        $result = $parser->parse('A {@see X} B {@broken C');

        self::assertInstanceOf(TaggedDescription::class, $result);
        self::assertCount(3, $result);

        [$before, $tag, $tail] = $result->components;

        self::assertPlainDescription('A ', $before);
        self::assertTagComponent('see', 'X', $tag);
        self::assertPlainDescription(' B {@broken C', $tail);
    }

    /**
     * @param iterable<string, string> $inputs
     * @return iterable<string, array{DescriptionParserInterface, string}>
     */
    private static function withParser(iterable $inputs): iterable
    {
        foreach (self::parserDataProvider() as $parserName => [$parser]) {
            foreach ($inputs as $inputName => $input) {
                yield $parserName . ': ' . $inputName => [$parser, $input];
            }
        }
    }

    private static function assertPlainDescription(string $expected, mixed $actual): void
    {
        self::assertInstanceOf(Description::class, $actual);
        self::assertSame($expected, $actual->value);
        self::assertSame($expected, (string) $actual);
    }

    private static function assertTagComponent(
        string $expectedName,
        ?string $expectedDescription,
        ComponentInterface $actual,
    ): void {
        self::assertInstanceOf(TagInterface::class, $actual);
        self::assertSame($expectedName, $actual->name);

        if ($expectedDescription === null) {
            self::assertNull($actual->description);

            return;
        }

        self::assertInstanceOf(Description::class, $actual->description);
        self::assertSame($expectedDescription, $actual->description->value);
    }
}
