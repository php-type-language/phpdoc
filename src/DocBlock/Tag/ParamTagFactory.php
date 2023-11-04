<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<ParamTag>
 */
final class ParamTagFactory extends TypedTagFactory
{
    public function create(string $tag): ParamTag
    {
        [$type, $description] = $this->extractType($tag);

        return new ParamTag(
            type: $type,
            description: $this->extractDescription($description),
        );
    }
}
