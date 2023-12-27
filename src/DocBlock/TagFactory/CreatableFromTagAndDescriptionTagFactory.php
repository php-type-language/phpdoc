<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Reader\OptionalTypeReader;
use TypeLang\PhpDocParser\DocBlock\Reader\TolerantTypeReader;
use TypeLang\PhpDocParser\DocBlock\Tag\CreatableFromTagAndDescriptionInterface;
use TypeLang\PhpDocParser\Exception\InvalidTagException;

/**
 * @template TTag of CreatableFromTagAndDescriptionInterface
 * @template-extends TagFactory<TTag>
 */
final class CreatableFromTagAndDescriptionTagFactory extends TagFactory
{
    private readonly TolerantTypeReader $types;

    /**
     * @param class-string<TTag> $class
     */
    public function __construct(
        private readonly string $class,
        Parser $parser = new Parser(true),
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->types = new TolerantTypeReader(
            reader: new OptionalTypeReader($parser),
        );

        parent::__construct($descriptions);
    }

    public function create(string $content): CreatableFromTagAndDescriptionInterface
    {
        $type = $this->types->read($content);
        $content = \substr($content, $type->offset);

        try {
            return $this->class::createFromTagAndDescription(
                type: $type->data,
                description: $this->createOptionalDescription($content),
            );
        } catch (\Throwable $e) {
            throw InvalidTagException::fromException($e);
        }
    }
}
