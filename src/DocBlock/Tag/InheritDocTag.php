<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

/**
 * TODO This tag doesnt support description: Should support for this
 *      functionality be removed?
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/inline-tags/inheritdoc.html#inheritdoc
 * @link https://docs.phpdoc.org/3.0/guide/guides/inheritance.html#inheritance
 */
final class InheritDocTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('inheritDoc', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
