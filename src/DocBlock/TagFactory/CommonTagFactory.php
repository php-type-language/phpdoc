<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\Tag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of Tag
 * @template-extends TagFactory<TTag>
 */
final class CommonTagFactory extends TagFactory
{
    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        parent::__construct($descriptions);
    }

    public function create(string $tag): Tag
    {
        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                description: $this->createDescription($tag),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
