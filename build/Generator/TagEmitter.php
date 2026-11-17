<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

use TypeLang\PhpDoc\Build\Model\Tag;

/**
 * Generates the empty tag class of a tag that adds nothing to its base class.
 */
final class TagEmitter
{
    public function emit(Tag $tag): string
    {
        $extends = $tag->extends;

        \assert($extends !== null);

        $namespace = Imports::getNamespace($tag->class);

        \assert($namespace !== '');

        $imports = new Imports($namespace);
        $base = $imports->add($extends);
        $docblock = $tag->summary;

        if ($tag->templateArguments !== []) {
            $arguments = \array_map($imports->add(...), $tag->templateArguments);

            $docblock .= "\n\n@template-extends " . $base . '<' . \implode(', ', $arguments) . '>';
        }

        $body = 'final class ' . Imports::getShortName($tag->class) . ' extends ' . $base . " {}\n";

        return PhpFile::render($namespace, $imports, $docblock, $tag->file, $body);
    }
}
