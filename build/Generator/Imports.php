<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Build\Generator;

/**
 * The `use` statements of one generated file.
 */
final class Imports
{
    /**
     * Imported classes by their short name.
     *
     * @var array<non-empty-string, non-empty-string>
     */
    private array $classes = [];

    public function __construct(
        /**
         * Namespace of the file, its classes need no import.
         */
        private readonly string $namespace,
    ) {}

    /**
     * Imports the class and returns the name to reference it by.
     *
     * @param non-empty-string $class fully qualified class name
     * @return non-empty-string
     * @throws \LogicException in case of two classes share the short name
     */
    public function add(string $class): string
    {
        $class = \ltrim($class, '\\');

        \assert($class !== '');

        $short = self::getShortName($class);

        if (self::getNamespace($class) === $this->namespace) {
            return $short;
        }

        $existing = $this->classes[$short] ?? null;

        if ($existing !== null && $existing !== $class) {
            throw new \LogicException(\sprintf('Classes %s and %s share the short name', $existing, $class));
        }

        $this->classes[$short] = $class;

        return $short;
    }

    /**
     * The `use` statements, one per line, in alphabetical order.
     */
    public function render(): string
    {
        $classes = \array_values($this->classes);

        \sort($classes, \SORT_STRING | \SORT_FLAG_CASE);

        return \implode('', \array_map(static fn(string $class): string => 'use ' . $class . ";\n", $classes));
    }

    /**
     * @param non-empty-string $class
     * @return non-empty-string
     */
    public static function getShortName(string $class): string
    {
        $position = \strrpos($class, '\\');

        if ($position === false) {
            return $class;
        }

        $result = \substr($class, $position + 1);

        \assert($result !== '');

        return $result;
    }

    public static function getNamespace(string $class): string
    {
        $position = \strrpos($class, '\\');

        if ($position === false) {
            return '';
        }

        return \substr($class, 0, $position);
    }
}
