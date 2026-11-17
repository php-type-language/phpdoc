<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use TypeLang\PhpDoc\Build\Model\LinkedModel;
use TypeLang\PhpDoc\Build\Model\ModelException;
use TypeLang\PhpDoc\Build\Model\ModelLinker;
use TypeLang\PhpDoc\Build\Model\ModelLoader;

/**
 * A command working on the linked model of the package.
 */
abstract class ModelCommand extends Command
{
    public function __construct(
        /**
         * Root directory of the package.
         */
        protected readonly string $root,
    ) {
        parent::__construct();
    }

    protected function getModelDirectory(): string
    {
        return $this->root . '/resources/model';
    }

    protected function getSourceDirectory(): string
    {
        return $this->root . '/src';
    }

    /**
     * Loads and links the model, reporting every problem of it.
     */
    protected function findModel(OutputInterface $output): ?LinkedModel
    {
        try {
            return (new ModelLinker())->link((new ModelLoader())->load($this->getModelDirectory()));
        } catch (ModelException $e) {
            foreach ($e->errors as $error) {
                $output->writeln('<error>' . $error . '</error>');
            }

            return null;
        }
    }
}
