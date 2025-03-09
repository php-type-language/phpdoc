<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateImplementsTag;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@implements`"
 * or "`@template-implements`" tags.
 *
 * See {@see TemplateImplementsTag} for details about this tag.
 */
final class TemplateImplementsTagFactory implements TagFactoryInterface
{
    private readonly TemplateExtendsTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new TemplateExtendsTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TemplateImplementsTag
    {
        $result = $this->factory->create($tag, $content, $descriptions);

        return new TemplateImplementsTag(
            name: $result->name,
            type: $result->type,
            description: $result->description,
        );
    }
}
