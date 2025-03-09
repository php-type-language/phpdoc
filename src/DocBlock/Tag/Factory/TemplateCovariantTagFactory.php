<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateCovariantTag;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@template-covariant`" tags.
 *
 * See {@see TemplateCovariantTag} for details about this tag.
 */
final class TemplateCovariantTagFactory implements TagFactoryInterface
{
    private readonly TemplateTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new TemplateTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TemplateCovariantTag
    {
        $result = $this->factory->create($tag, $content, $descriptions);

        return new TemplateCovariantTag(
            name: $result->name,
            template: $result->template,
            type: $result->type,
            description: $result->description,
        );
    }
}
