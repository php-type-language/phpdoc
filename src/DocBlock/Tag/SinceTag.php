<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

/**
 * TODO Add version support.
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/since.html#since
 */
final class SinceTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('since', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
