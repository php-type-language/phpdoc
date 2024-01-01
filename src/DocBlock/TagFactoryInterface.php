<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock;

use TypeLang\PhpDoc\Parser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\InvalidTagInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\TagInterface;

/**
 * @template TReturn of TagInterface
 */
interface TagFactoryInterface
{
    /**
     * Factory method responsible for instantiating the correct tag type.
     *
     * @param string $content The text for this tag, including description.
     *
     * @return TReturn|InvalidTagInterface A new tag object.
     */
    public function create(string $content): TagInterface;

    /**
     * @psalm-immutable
     */
    public function withDescriptionFactory(?DescriptionFactoryInterface $factory): self;

    /**
     * @psalm-immutable
     */
    public function getDescriptionFactory(): ?DescriptionFactoryInterface;
}
