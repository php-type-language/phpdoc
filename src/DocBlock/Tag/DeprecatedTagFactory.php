<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<DeprecatedTag>
 */
final class DeprecatedTagFactory extends TagFactory
{
    public function create(string $tag): DeprecatedTag
    {
        return new DeprecatedTag(
            description: $this->extractDescription($tag),
        );
    }
}
