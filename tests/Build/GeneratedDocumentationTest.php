<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Build;

use TypeLang\PhpDoc\Build\Documentation\DocumentationGenerator;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * The tag pages of the documentation are up to date with the model. Run
 * `php libs/phpdoc/bin/build docs` after changing the model.
 *
 * The documentation lives in the monorepository only, the test is skipped
 * without it.
 */
final class GeneratedDocumentationTest extends TestCase
{
    private const DOCUMENTATION_DIRECTORY = __DIR__ . '/../../../../docs/Writerside/topics/phpdoc/tags';

    public function testTagPagesMatchModel(): void
    {
        if (!\is_dir(self::DOCUMENTATION_DIRECTORY)) {
            self::markTestSkipped('The documentation is not available outside of the monorepository');
        }

        $pages = (new DocumentationGenerator())->generate(ModelTest::getModel(), self::DOCUMENTATION_DIRECTORY);

        self::assertSame([], \array_keys($pages), 'Tag pages are outdated or missing');
    }
}
