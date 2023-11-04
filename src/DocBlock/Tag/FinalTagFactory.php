<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<FinalTag>
 */
final class FinalTagFactory extends TagFactory
{
    public function create(string $tag): FinalTag
    {
        return new FinalTag(
            description: $this->extractDescription($tag),
        );
    }
}
