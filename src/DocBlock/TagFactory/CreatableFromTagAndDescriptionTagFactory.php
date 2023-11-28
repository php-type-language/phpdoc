<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromTagAndDescriptionInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of CreatableFromTagAndDescriptionInterface
 * @template-extends TypedTagFactory<TTag>
 */
final class CreatableFromTagAndDescriptionTagFactory extends TypedTagFactory
{
    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        Parser $parser = new Parser(true),
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        parent::__construct($parser, $descriptions);
    }

    public function create(string $tag): CreatableFromTagAndDescriptionInterface
    {
        [$type, $description] = $this->types->extractTypeOrMixed($tag);

        try {
            return $this->class::createFromTagAndDescription(
                type: $type,
                description: $this->createDescription($description),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
