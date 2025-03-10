<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\TemplateTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@template-contravariant`" tags.
 *
 * See {@see TemplateContravariantTag} for details about this tag.
 */
final class TemplateContravariantTagFactory implements TagFactoryInterface
{
    private readonly TemplateTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new TemplateTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TemplateContravariantTag
    {
        $result = $this->factory->create($tag, $content, $descriptions);

        return new TemplateContravariantTag(
            name: $result->name,
            template: $result->template,
            type: $result->type,
            description: $result->description,
        );
    }
}
