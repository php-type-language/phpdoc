<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<AuthorTag>
 */
final class AuthorTagFactory extends TagFactory
{
    public function create(string $tag): AuthorTag
    {
        return new AuthorTag(
            description: $this->extractDescription($tag),
        );
    }
}
