<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use TypeLang\PhpDoc\Build\Generator\Generator;
use TypeLang\PhpDoc\Build\Generator\PhpFile;
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

    public function testDirectivesAreEscaped(): void
    {
        self::assertSame(
            "The `@\u{200B}deprecated` tag, `@\u{200B}phpstan-ignore-line`, {@see Foo}, `@param`",
            PhpFile::escapeDirectives('The `@deprecated` tag, `@phpstan-ignore-line`, {@see Foo}, `@param`'),
        );
    }
}
