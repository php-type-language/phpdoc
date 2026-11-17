<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

use TypeLang\Parser\TypeParser;
use TypeLang\PhpDoc\Build\Model\CombinatorArguments;
use TypeLang\PhpDoc\Build\Model\LinkedModel;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Platform\PlatformInterface;

/**
 * Generates what the library ships to {@see \TypeLang\PhpDoc\DocBlockParser}:
 * the combinators of the parser itself and the list of the built-in
 * platforms.
 */
final class BuiltinEmitter
{
    public const COMBINATORS_CLASS = 'TypeLang\\PhpDoc\\Internal\\BaseCombinators';

    public const PLATFORMS_CLASS = 'TypeLang\\PhpDoc\\Internal\\BuiltinPlatforms';

    private const INTERNAL = "@internal this is an internal library class, please do not use it in your code\n"
        . '@psalm-internal TypeLang\\PhpDoc';

    public function emitCombinators(LinkedModel $model): string
    {
        $namespace = Imports::getNamespace(self::COMBINATORS_CLASS);

        \assert($namespace !== '');

        $imports = new Imports($namespace);
        $typeParser = $imports->add(TypeParser::class);
        $entries = '';

        foreach ($model->getBaseCombinators() as $combinator) {
            if (!$combinator->isInstanceGenerated) {
                continue;
            }

            \assert(\class_exists($combinator->class));

            $class = $imports->add($combinator->class);
            $arguments = '';

            if (CombinatorArguments::needsTypeParser($combinator->class)) {
                $arguments = '$typeParser';
            }

            $entries .= '            ' . $class . '::NAME => new ' . $class . '(' . $arguments . "),\n";
        }

        $docblock = "The combinators the parser provides to the tags of every platform.\n\n"
            . self::INTERNAL . "\n\n"
            . '@phpstan-import-type CombinatorType from ' . $imports->add(CombinatorInterface::class);

        $body = 'final class ' . Imports::getShortName(self::COMBINATORS_CLASS) . "\n{\n"
            . PhpFile::renderDocblock('@return array<non-empty-string, CombinatorType>', 4)
            . '    public static function create(' . $typeParser . " \$typeParser): array\n"
            . "    {\n"
            . "        return [\n"
            . $entries
            . "        ];\n"
            . "    }\n"
            . "}\n";

        return PhpFile::render($namespace, $imports, $docblock, 'combinators.xml', $body);
    }

    public function emitPlatforms(LinkedModel $model): string
    {
        $namespace = Imports::getNamespace(self::PLATFORMS_CLASS);

        \assert($namespace !== '');

        $imports = new Imports($namespace);
        $entries = '';

        foreach ($model->packages as $linked) {
            $entries .= '            new ' . $imports->add($linked->package->class) . "(),\n";
        }

        $docblock = "Every platform the library ships, in no particular order: no name is\n"
            . "registered by two of them.\n\n"
            . self::INTERNAL;

        $body = 'final class ' . Imports::getShortName(self::PLATFORMS_CLASS) . "\n{\n"
            . PhpFile::renderDocblock('@return non-empty-list<' . $imports->add(PlatformInterface::class) . '>', 4)
            . "    public static function create(): array\n"
            . "    {\n"
            . "        return [\n"
            . $entries
            . "        ];\n"
            . "    }\n"
            . "}\n";

        return PhpFile::render($namespace, $imports, $docblock, '*.xml', $body);
    }
}
