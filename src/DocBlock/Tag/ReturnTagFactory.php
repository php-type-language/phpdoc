<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<ReturnTag>
 */
final class ReturnTagFactory extends TypedTagFactory
{
    public function create(string $tag): ReturnTag
    {
        [$type, $description] = $this->extractType($tag);

        return new ReturnTag(
            type: $type,
            description: $this->extractDescription($description),
        );
    }
}
