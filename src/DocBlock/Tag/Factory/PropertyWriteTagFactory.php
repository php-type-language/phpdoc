<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\PropertyWriteTag;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@property-write`" tags.
 *
 * See {@see PropertyWriteTag} for details about this tag.
 */
final class PropertyWriteTagFactory implements TagFactoryInterface
{
    private readonly PropertyTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new PropertyTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): PropertyWriteTag
    {
        $property = $this->factory->create($tag, $content, $descriptions);

        return new PropertyWriteTag(
            name: $property->name,
            type: $property->getType(),
            variable: $property->getVariable(),
            description: $property->description,
        );
    }
}
