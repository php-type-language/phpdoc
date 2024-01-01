<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\TagFactory;

use TypeLang\PhpDoc\Parser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CreatableFromDescriptionInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\Tag;
use TypeLang\PhpDoc\Parser\Exception\InvalidTagException;

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
