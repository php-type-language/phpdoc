<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TypeLang\PhpDoc\Build\Documentation\DocumentationGenerator;

/**
 * Updates the grammar and the examples on the tag pages of the documentation
 * and creates the pages missing for the registered names.
 */
#[AsCommand(name: 'docs', description: 'Update the tag pages of the documentation from the model')]
final class DocsCommand extends ModelCommand
{
    protected function configure(): void
    {
        $this->addOption(
            name: 'directory',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Directory of the tag pages',
            default: \dirname($this->root, 2) . '/docs/Writerside/topics/phpdoc/tags',
        );

        $this->addOption(
            name: 'check',
            mode: InputOption::VALUE_NONE,
            description: 'Only report the pages that differ, writing nothing',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $model = $this->findModel($output);

        if ($model === null) {
            return self::FAILURE;
        }

        $directory = $input->getOption('directory');

        \assert(\is_string($directory));

        if (!\is_dir($directory)) {
            $output->writeln('<error>Directory ' . $directory . ' not found</error>');

            return self::FAILURE;
        }

        $pages = (new DocumentationGenerator())->generate($model, $directory);
        $isCheck = (bool) $input->getOption('check');

        foreach ($pages as $file => $contents) {
            $isNew = !\is_file($directory . '/' . $file);

            if (!$isCheck) {
                \file_put_contents($directory . '/' . $file, $contents);
            }

            $output->writeln(\sprintf('  <info>%s</info> %s', $isNew ? 'created' : 'updated', $file));

            if ($isNew) {
                $output->writeln('    <comment>add the page to the table of contents (*.tree)</comment>');
            }
        }

        $output->writeln(\sprintf('%d pages differ from the model', \count($pages)));

        if ($isCheck && $pages !== []) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
