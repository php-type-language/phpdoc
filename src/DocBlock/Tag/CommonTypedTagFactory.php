<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\ParserInterface;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

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
        [$type, $description] = $this->types->extractTypeOrMixed($tag);

        try {
            /** @psalm-suppress UnsafeInstantiation */
            return new ($this->class)(
                type: $type,
                description: $this->extractDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}