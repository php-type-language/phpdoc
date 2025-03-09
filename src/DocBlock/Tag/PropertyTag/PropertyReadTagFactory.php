<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PropertyTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@property-read`" tags.
 *
 * See {@see PropertyReadTag} for details about this tag.
 */
final class PropertyReadTagFactory implements TagFactoryInterface
{
    private readonly PropertyTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new PropertyTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): PropertyReadTag
    {
        $property = $this->factory->create($tag, $content, $descriptions);

        return new PropertyReadTag(
            name: $property->name,
            type: $property->type,
            variable: $property->variable,
            description: $property->description,
        );
    }
}
