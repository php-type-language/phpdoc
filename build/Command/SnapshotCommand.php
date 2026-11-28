<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeLang\PhpDoc\Build\Snapshot\RegistryDumper;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\Platform\PlatformInterface;

/**
 * Rewrites the registry snapshots of the tests after an intended change of
 * the platforms. The diff of the snapshots is the review of the change.
 */
#[AsCommand(name: 'snapshot', description: 'Rewrite the registry snapshots of the tests')]
final class SnapshotCommand extends ModelCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $model = $this->loadModel($output);

        if ($model === null) {
            return self::FAILURE;
        }

        $directory = $this->root . '/tests/Platform/snapshot';
        $dumper = new RegistryDumper();

        foreach ($model->packages as $name => $linked) {
            $platform = new ($linked->package->class)();

            \assert($platform instanceof PlatformInterface);

            $this->write($output, $directory . '/' . $name . '.txt', $dumper->dumpPlatform($platform));
        }

        $this->write($output, $directory . '/default.txt', $dumper->dumpSummary(DocBlockParser::create()));

        return self::SUCCESS;
    }

    private function write(OutputInterface $output, string $pathname, string $contents): void
    {
        $existing = null;

        if (\is_file($pathname)) {
            $existing = \file_get_contents($pathname);
        }

        if ($existing === $contents) {
            return;
        }

        \file_put_contents($pathname, $contents);

        $output->writeln('  <info>written</info> ' . \basename($pathname));
    }
}
