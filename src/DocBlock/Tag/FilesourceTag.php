<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO This tag doesnt support description: Should support for this
 *      functionality be removed?
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/filesource.html#filesource
 */
final class FilesourceTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('filesource', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
