<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeLang\PhpDoc\Build\Generator\Generator;

/**
 * Checks the model and that the generated sources are up to date with it,
 * writing nothing.
 */
#[AsCommand(name: 'check', description: 'Check the model and the generated sources')]
final class CheckCommand extends ModelCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $model = $this->loadModel($output);

        if ($model === null) {
            return self::FAILURE;
        }

        $generator = new Generator();
        $diff = $generator->diff($generator->generate($model), $this->getSourceDirectory());

        foreach ($diff->written as $path) {
            $output->writeln('  <error>outdated</error> src/' . $path);
        }

        foreach ($diff->stale as $path) {
            $output->writeln('  <error>stale</error> src/' . $path);
        }

        if (!$diff->isEmpty()) {
            $output->writeln('Run "bin/build generate" to update the sources');

            return self::FAILURE;
        }

        $output->writeln(\sprintf(
            'The model is valid: %d tags in %d packages, the sources are up to date',
            \count($model->tags),
            \count($model->packages),
        ));

        return self::SUCCESS;
    }
}
