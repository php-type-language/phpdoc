<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/todo.html#todo
 */
final class TodoTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('todo', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
