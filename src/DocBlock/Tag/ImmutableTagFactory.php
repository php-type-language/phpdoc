<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<ImmutableTag>
 */
final class ImmutableTagFactory extends TagFactory
{
    public function create(string $tag): ImmutableTag
    {
        return new ImmutableTag(
            description: $this->extractDescription($tag),
        );
    }
}
