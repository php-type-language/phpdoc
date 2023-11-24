<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDocParser\DocBlock\TagFactoryInterface;

/**
 * @template TReturn of TagInterface
 *
 * @template-implements TagFactoryInterface<TReturn>
 */
abstract class TagFactory implements TagFactoryInterface
{
    public function __construct(
        protected ?DescriptionFactoryInterface $descriptions = null,
    ) {}

    protected function createDescription(?string $description): ?Description
    {
        if ($description !== null) {
            return $this->descriptions?->create($description)
                ?? Description::create($description)
            ;
        }

        return null;
    }

    public function withDescriptionFactory(?DescriptionFactoryInterface $factory): self
    {
        $self = clone $this;
        $self->descriptions = $factory;

        return $self;
    }

    public function getDescriptionFactory(): ?DescriptionFactoryInterface
    {
        return $this->descriptions;
    }
}
