<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Reader\ReferenceReader;
use TypeLang\PhpDocParser\DocBlock\Tag\Tag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of Tag
 * @template-extends TagFactory<TTag>
 */
final class LinkTagFactory extends TagFactory
{
    private readonly ReferenceReader $references;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->references = new ReferenceReader();

        parent::__construct($descriptions);
    }

    public function create(string $content): Tag
    {
        $reference = $this->references->read($content);

        $description = \substr($content, $reference->offset);

        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                $reference->data,
                $this->createDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
