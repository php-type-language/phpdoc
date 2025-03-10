<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PropertyTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeParserReader;
use TypeLang\PHPDoc\Parser\Content\VariableNameReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@property`" tags.
 *
 * See {@see PropertyTag} for details about this tag.
 */
final class PropertyTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): PropertyTag
    {
        $stream = new Stream($content);
        $type = null;

        if (!\str_starts_with($stream->value, '$')) {
            $type = $stream->apply(new TypeParserReader($tag, $this->parser));
        }

        $variable = $stream->apply(new VariableNameReader($tag));

        return new PropertyTag(
            name: $tag,
            type: $type,
            variable: $variable,
            description: \trim($stream->value) !== ''
                ? $descriptions->parse(\rtrim($stream->value))
                : null,
        );
    }
}
