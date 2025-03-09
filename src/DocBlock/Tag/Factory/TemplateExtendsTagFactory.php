<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Factory;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Content\Stream;
use TypeLang\PHPDoc\DocBlock\Content\TypeParserReader;
use TypeLang\PHPDoc\DocBlock\Tag\TemplateExtendsTag;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@extends`"
 * or "`@template-extends`" tags.
 *
 * See {@see TemplateExtendsTag} for details about this tag.
 */
final class TemplateExtendsTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TemplateExtendsTag
    {
        $stream = new Stream($content);

        $type = $stream->apply(new TypeParserReader($tag, $this->parser));

        return new TemplateExtendsTag(
            name: $tag,
            type: $type,
            description: \trim($stream->value) !== ''
                ? $descriptions->parse(\rtrim($stream->value))
                : null,
        );
    }
}
