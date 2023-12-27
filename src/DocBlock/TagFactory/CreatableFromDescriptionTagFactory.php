<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromDescriptionInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\Tag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of CreatableFromDescriptionInterface
 * @template-extends TagFactory<TTag>
 */
final class CreatableFromDescriptionTagFactory extends TagFactory
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

    public function create(string $content): Tag
    {
        try {
            return ($this->class)::createFromDescription(
                description: $this->createDescription($content),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
