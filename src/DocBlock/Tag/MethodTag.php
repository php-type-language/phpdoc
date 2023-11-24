<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add "static" keyword support.
 * TODO Add return type support.
 * TODO Add method name support.
 * TODO Add method parameters support.
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/method.html#method
 */
final class MethodTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('method', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
