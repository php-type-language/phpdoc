<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<InternalTag>
 */
final class InternalTagFactory extends TagFactory
{
    public function create(string $tag): InternalTag
    {
        return new InternalTag(
            description: $this->extractDescription($tag),
        );
    }
}
