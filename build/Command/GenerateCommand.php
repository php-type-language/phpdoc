<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeLang\PhpDoc\Build\Generator\Generator;

/**
 * Generates the definitions, the tag classes and the platforms of the model
 * into the source directory.
 */
#[AsCommand(name: 'generate', description: 'Generate the sources from the model')]
final class GenerateCommand extends ModelCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $model = $this->loadModel($output);

        if ($model === null) {
            return self::FAILURE;
        }

        $generator = new Generator();
        $files = $generator->generate($model);
        $diff = $generator->write($files, $this->getSourceDirectory());

        foreach ($diff->written as $path) {
            $output->writeln('  <info>written</info> src/' . $path);
        }

        foreach ($diff->stale as $path) {
            $output->writeln('  <comment>removed</comment> src/' . $path);
        }

        $output->writeln(\sprintf(
            '%d files generated, %d written, %d removed',
            \count($files),
            \count($diff->written),
            \count($diff->stale),
        ));

        return self::SUCCESS;
    }
}
