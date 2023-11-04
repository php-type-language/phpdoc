<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Reader\DocBlock\Description;
use TypeLang\Reader\DocBlock\DescriptionFactoryInterface;
use TypeLang\Reader\DocBlock\TagFactoryInterface;

/**
 * @template TReturn of TagInterface
 *
 * @template-implements TagFactoryInterface<TReturn>
 */
abstract class TagFactory implements TagFactoryInterface
{
    public function __construct(
        protected readonly DescriptionFactoryInterface $descriptions,
    ) {}

    protected function extractDescription(?string $description): ?Description
    {
        if ($description !== null) {
            return $this->descriptions->create($description);
        }

        return null;
    }
}
