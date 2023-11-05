<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\ParserInterface;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;

/**
 * @template TTag of TypedTag
 * @template-extends TypedTagFactory<TTag>
 */
final class CommonTypedTagFactory extends TypedTagFactory
{
    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        ParserInterface $parser,
        DescriptionFactoryInterface $descriptions,
    ) {
        parent::__construct($parser, $descriptions);
    }

    public function create(string $tag): TypedTag
    {
        [$type, $description] = $this->extractType($tag);

        /** @psalm-suppress UnsafeInstantiation */
        return new ($this->class)(
            type: $type,
            description: $this->extractDescription($description),
        );
    }
}
