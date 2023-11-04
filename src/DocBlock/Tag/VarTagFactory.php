<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @template-extends TypedTagFactory<VarTag>
 */
final class VarTagFactory extends TypedTagFactory
{
    public function create(string $tag): VarTag
    {
        [$type, $description] = $this->extractType($tag);

        return new VarTag(
            type: $type,
            description: $this->extractDescription($description),
        );
    }
}
