<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\TagFactory;

use TypeLang\PhpDoc\Parser\DocBlock\Description;
use TypeLang\PhpDoc\Parser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Parser\DocBlock\TagFactoryInterface;

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
                ?? Description::create($description);
        }

        return null;
    }

    protected function createOptionalDescription(?string $description): ?Description
    {
        if ($description === null || \trim($description) === '') {
            return null;
        }

        return $this->descriptions?->create($description)
            ?? Description::create($description);
    }

    public function setDescriptionFactory(?DescriptionFactoryInterface $factory): void
    {
        $this->descriptions = $factory;
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
