<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

interface CreatableFromDescriptionInterface extends TagInterface
{
    /**
     * Creates a new tag instance from an arbitrary optional string-like argument.
     */
    public static function createFromDescription(\Stringable|string|null $description = null): self;
}
