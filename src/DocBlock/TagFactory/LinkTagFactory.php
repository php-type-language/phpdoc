<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;


use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagReferenceExtractor;
use TypeLang\PhpDocParser\DocBlock\Tag\Tag;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of Tag
 * @template-extends TagFactory<TTag>
 */
final class LinkTagFactory extends TagFactory
{
    private readonly TagReferenceExtractor $references;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->references = new TagReferenceExtractor();

        parent::__construct($descriptions);
    }

    public function create(string $tag): Tag
    {
        [$reference, $description] = $this->references->extract($tag);

        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                $reference,
                description: $this->createDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
