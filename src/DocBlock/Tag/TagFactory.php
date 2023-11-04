<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\TagFactoryInterface;

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
