<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<ThrowsTag>
 */
final class ThrowsTagFactory extends TypedTagFactory
{
    public function create(string $tag): ThrowsTag
    {
        [$type, $description] = $this->extractType($tag);

        return new ThrowsTag(
            type: $type,
            description: $this->extractDescription($description),
        );
    }
}
