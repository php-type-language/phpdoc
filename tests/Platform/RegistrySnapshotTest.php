<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Platform;

use PHPUnit\Framework\Attributes\DataProvider;
use TypeLang\PhpDoc\Build\Snapshot\RegistryDumper;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Platform\PhanPlatform;
use TypeLang\PhpDoc\Platform\PhpCodeSnifferPlatform;
use TypeLang\PhpDoc\Platform\PhpDocumentorPlatform;
use TypeLang\PhpDoc\Platform\PhpStanPlatform;
use TypeLang\PhpDoc\Platform\PhpStormPlatform;
use TypeLang\PhpDoc\Platform\PlatformInterface;
use TypeLang\PhpDoc\Platform\PsalmPlatform;
use TypeLang\PhpDoc\Platform\StandardPlatform;
use TypeLang\PhpDoc\Tests\TestCase;

/**
 * Guards the runtime contract of the platforms against unintended changes.
 *
 * Run `php libs/phpdoc/bin/build snapshot` to rewrite the snapshots after an
 * intended change, then review the diff.
 */
final class RegistrySnapshotTest extends TestCase
{
    private const SNAPSHOT_DIRECTORY = __DIR__ . '/snapshot';

    #[DataProvider('platformDataProvider')]
    public function testPlatformMatchesSnapshot(string $file, PlatformInterface $platform): void
    {
        $actual = (new RegistryDumper())->dumpPlatform($platform);

        self::assertSnapshot($file, $actual);
    }

    public function testDefaultParserMatchesSnapshot(): void
    {
        $parser = DocBlockParser::createDefault();
        $dumper = new RegistryDumper();

        self::assertSnapshot('default.txt', $dumper->dumpSummary($parser));
    }

    /**
     * @return iterable<non-empty-string, array{non-empty-string, PlatformInterface}>
     */
    public static function platformDataProvider(): iterable
    {
        yield 'standard' => ['standard.txt', new StandardPlatform()];
        yield 'phpDocumentor' => ['phpdocumentor.txt', new PhpDocumentorPlatform()];
        yield 'PHPStan' => ['phpstan.txt', new PhpStanPlatform()];
        yield 'Psalm' => ['psalm.txt', new PsalmPlatform()];
        yield 'Phan' => ['phan.txt', new PhanPlatform()];
        yield 'PhpStorm' => ['phpstorm.txt', new PhpStormPlatform()];
        yield 'PHP CodeSniffer' => ['phpcs.txt', new PhpCodeSnifferPlatform()];
    }

    /**
     * @param non-empty-string $file
     */
    private static function assertSnapshot(string $file, string $actual): void
    {
        self::assertStringEqualsFile(self::SNAPSHOT_DIRECTORY . '/' . $file, $actual);
    }
}
