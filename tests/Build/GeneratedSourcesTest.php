<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PhpDoc\Build\Generator\DefinitionEmitter;
use TypeLang\PhpDoc\Build\Generator\Generator;
use TypeLang\PhpDoc\Build\Generator\PhpFile;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * The generated sources are up to date with the model and edited nowhere by
 * hand. Run `php libs/phpdoc/bin/build generate` after changing the model.
 */
final class GeneratedSourcesTest extends TestCase
{
    private const SOURCE_DIRECTORY = __DIR__ . '/../../src';

    public function testSourcesMatchModel(): void
    {
        $generator = new Generator();

        $diff = $generator->diff($generator->generate(ModelTest::getModel()), self::SOURCE_DIRECTORY);

        self::assertSame([], $diff->written, 'Generated files are outdated or edited by hand');
        self::assertSame([], $diff->stale, 'Generated files are no longer produced by the model');
    }

    public function testDefinitionOfEveryTagCarriesDescriptionOfModel(): void
    {
        foreach (ModelTest::getModel()->tags as $tag) {
            $class = $tag->getDefinitionClass();
            $definition = new $class();

            self::assertInstanceOf(TagDefinitionInterface::class, $definition);
            self::assertSame(
                DefinitionEmitter::getDescription($tag),
                $definition->description,
                \sprintf('Description of "@%s" differs from the model', $tag->name),
            );
        }
    }

    public function testNowdocIndentsLinesButEmptyOnes(): void
    {
        self::assertSame("<<<'TEXT'\n    a\n\n    b\n    TEXT", PhpFile::renderNowdoc("a\n\nb", 4));
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string}>
     */
    public static function closingLineDataProvider(): iterable
    {
        yield 'marker' => ["a\nTEXT"];
        yield 'indented marker' => ["a\n  TEXT"];
        yield 'marker followed by a comma' => ["a\nTEXT, b"];
    }

    #[DataProvider('closingLineDataProvider')]
    public function testNowdocRejectsLineClosingIt(string $text): void
    {
        $this->expectException(\LogicException::class);

        PhpFile::renderNowdoc($text, 4);
    }

    public function testNowdocAcceptsWordStartingWithMarker(): void
    {
        self::assertSame("<<<'TEXT'\n    TEXTS\n    TEXT", PhpFile::renderNowdoc('TEXTS', 4));
    }

    public function testDirectivesAreEscaped(): void
    {
        self::assertSame(
            "The `@\u{200B}deprecated` tag, `@\u{200B}phpstan-ignore-line`, {@see Foo}, `@param`",
            PhpFile::escapeDirectives('The `@deprecated` tag, `@phpstan-ignore-line`, {@see Foo}, `@param`'),
        );
    }
}
