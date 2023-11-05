<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of Tag
 * @template-extends TypedTagFactory<TTag>
 */
final class CommonTagFactory extends TagFactory
{
    /**
     * @param class-string<TTag> $class
     * @param DescriptionFactoryInterface $descriptions
     */
    public function __construct(
        private readonly string $class,
        DescriptionFactoryInterface $descriptions,
    ) {
        parent::__construct($descriptions);
    }

    public function create(string $tag): Tag
    {
        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                description: $this->extractDescription($tag),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
