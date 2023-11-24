<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add required location support
 * TODO Add optional start line support
 * TODO Add optional number of lines support
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/example.html#example
 */
final class ExampleTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('example', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
