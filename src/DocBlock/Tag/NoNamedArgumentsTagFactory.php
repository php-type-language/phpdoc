<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<NoNamedArgumentsTag>
 */
final class NoNamedArgumentsTagFactory extends TagFactory
{
    public function create(string $tag): NoNamedArgumentsTag
    {
        return new NoNamedArgumentsTag(
            description: $this->extractDescription($tag),
        );
    }
}
