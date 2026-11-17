<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

use TypeLang\PhpDoc\Build\Model\LinkedPackage;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Platform\Platform;

/**
 * Generates the platform class of a package.
 */
final class PlatformEmitter
{
    public function emit(LinkedPackage $linked): string
    {
        $package = $linked->package;
        $namespace = Imports::getNamespace($package->class);

        \assert($namespace !== '');

        $imports = new Imports($namespace);
        $docblock = $package->description;

        $methods = [$this->emitTags($linked, $imports)];

        if ($linked->aliases !== []) {
            $methods[] = $this->emitAliases($linked);
        }

        if ($linked->combinators !== []) {
            $methods[] = $this->emitCombinators($linked, $imports);
            $docblock .= "\n\n@phpstan-import-type CombinatorType from " . $imports->add(CombinatorInterface::class);
        }

        $body = 'final class ' . Imports::getShortName($package->class)
            . ' extends ' . $imports->add(Platform::class) . "\n{\n"
            . \implode("\n", $methods)
            . "}\n";

        return PhpFile::render($namespace, $imports, $docblock, $package->file, $body);
    }

    private function emitTags(LinkedPackage $linked, Imports $imports): string
    {
        $entries = '';

        foreach ($linked->tags as $name => $tag) {
            $definition = $imports->add($tag->getDefinitionClass());
            $key = PhpFile::renderString($name);

            if ($name === $tag->name) {
                $key = $definition . '::getCanonicalName()';
            }

            $entries .= '            ' . $key . ' => new ' . $definition . "(),\n";
        }

        $type = self::getKeyType(\array_keys($linked->tags));

        return PhpFile::renderDocblock(
            '@return iterable<' . $type . ', ' . $imports->add(TagDefinitionInterface::class) . '>',
            4,
        )
            . "    public function getTags(): iterable\n"
            . "    {\n"
            . "        return [\n"
            . $entries
            . "        ];\n"
            . "    }\n";
    }

    private function emitAliases(LinkedPackage $linked): string
    {
        $entries = '';

        foreach ($linked->aliases as $alias => $target) {
            $entries .= '            ' . PhpFile::renderString($alias) . ' => ' . PhpFile::renderString($target) . ",\n";
        }

        $type = self::getKeyType([...\array_keys($linked->aliases), ...\array_values($linked->aliases)]);

        return PhpFile::renderDocblock('@return iterable<' . $type . ', ' . $type . '>', 4)
            . "    public function getAliases(): iterable\n"
            . "    {\n"
            . "        return [\n"
            . $entries
            . "        ];\n"
            . "    }\n";
    }

    private function emitCombinators(LinkedPackage $linked, Imports $imports): string
    {
        $entries = '';

        foreach ($linked->combinators as $combinator) {
            $class = $imports->add($combinator->class);

            $entries .= '            ' . $class . '::NAME => new ' . $class . "(),\n";
        }

        return PhpFile::renderDocblock('@return iterable<non-empty-string, CombinatorType>', 4)
            . "    public function getCombinators(): iterable\n"
            . "    {\n"
            . "        return [\n"
            . $entries
            . "        ];\n"
            . "    }\n";
    }

    /**
     * @param list<string|int> $names
     */
    private static function getKeyType(array $names): string
    {
        foreach ($names as $name) {
            if (\strtolower((string) $name) !== (string) $name) {
                return 'non-empty-string';
            }
        }

        return 'non-empty-lowercase-string';
    }
}
