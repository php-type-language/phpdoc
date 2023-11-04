<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock;

use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

/**
 * @template TReturn of TagInterface
 */
interface TagFactoryInterface
{
    /**
     * Factory method responsible for instantiating the correct tag type.
     *
     * @param string $tag The text for this tag, including description.
     *
     * @return TagInterface A new tag object.
     */
    public function create(string $tag): TagInterface;
}