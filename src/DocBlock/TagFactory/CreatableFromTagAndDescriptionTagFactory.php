<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDoc\Parser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\OptionalTypeReader;
use TypeLang\PhpDoc\Parser\DocBlock\Reader\TolerantTypeReader;
use TypeLang\PhpDoc\Parser\DocBlock\Tag\CreatableFromTagAndDescriptionInterface;
use TypeLang\PhpDoc\Parser\Exception\InvalidTagException;

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
