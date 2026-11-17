<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

use TypeLang\PhpDoc\Build\Model\LinkedModel;

/**
 * Generates the sources of the model and keeps the source directory in sync
 * with it: writes the changed files and removes the generated files the model
 * no longer produces.
 */
final class Generator
{
    private const ROOT_NAMESPACE = 'TypeLang\\PhpDoc\\';

    /**
     * Contents of every generated file by its path relative to the source
     * directory.
     *
     * @return array<non-empty-string, string>
     */
    public function generate(LinkedModel $model): array
    {
        $result = [];
        $definitions = new DefinitionEmitter($model);
        $tags = new TagEmitter();
        $platforms = new PlatformEmitter();

        foreach ($model->tags as $tag) {
            if ($tag->isDefinitionGenerated) {
                $result[self::getPath($tag->getDefinitionClass())] = $definitions->emit($tag);
            }

            if ($tag->extends !== null) {
                $result[self::getPath($tag->class)] = $tags->emit($tag);
            }
        }

        foreach ($model->packages as $package) {
            $result[self::getPath($package->package->class)] = $platforms->emit($package);
        }

        $builtin = new BuiltinEmitter();

        $result[self::getPath(BuiltinEmitter::COMBINATORS_CLASS)] = $builtin->emitCombinators($model);
        $result[self::getPath(BuiltinEmitter::PLATFORMS_CLASS)] = $builtin->emitPlatforms($model);

        \ksort($result, \SORT_STRING);

        return $result;
    }

    /**
     * Compares the generated files with the source directory.
     *
     * @param array<non-empty-string, string> $files
     */
    public function diff(array $files, string $directory): GeneratorDiff
    {
        $written = [];
        $stale = [];

        foreach ($files as $path => $contents) {
            $existing = @\file_get_contents($directory . '/' . $path);

            if ($existing !== $contents) {
                $written[] = $path;
            }
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            \assert($file instanceof \SplFileInfo);

            $path = \str_replace('\\', '/', \substr($file->getPathname(), \strlen($directory) + 1));

            if ($path === '' || isset($files[$path]) || $file->getExtension() !== 'php') {
                continue;
            }

            if (PhpFile::isGenerated((string) \file_get_contents($file->getPathname()))) {
                $stale[] = $path;
            }
        }

        \sort($stale);

        return new GeneratorDiff($written, $stale);
    }

    /**
     * Writes the changed files and removes the stale ones.
     *
     * @param array<non-empty-string, string> $files
     */
    public function write(array $files, string $directory): GeneratorDiff
    {
        $diff = $this->diff($files, $directory);

        foreach ($diff->written as $path) {
            $pathname = $directory . '/' . $path;

            if (!\is_dir(\dirname($pathname))) {
                \mkdir(\dirname($pathname), 0o777, true);
            }

            \file_put_contents($pathname, $files[$path]);
        }

        foreach ($diff->stale as $path) {
            \unlink($directory . '/' . $path);
        }

        return $diff;
    }

    /**
     * @param non-empty-string $class
     * @return non-empty-string
     */
    private static function getPath(string $class): string
    {
        if (!\str_starts_with($class, self::ROOT_NAMESPACE)) {
            throw new \LogicException(\sprintf('Class %s is outside of %s', $class, self::ROOT_NAMESPACE));
        }

        return \str_replace('\\', '/', \substr($class, \strlen(self::ROOT_NAMESPACE))) . '.php';
    }
}
