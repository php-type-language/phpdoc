<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\VarTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\OptionalVariableNameReader;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeParserReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@var`" tags.
 *
 * See {@see VarTag} for details about this tag.
 */
final class VarTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): VarTag
    {
        $stream = new Stream($content);

        $type = $stream->apply(new TypeParserReader($tag, $this->parser));
        $variable = $stream->apply(new OptionalVariableNameReader());

        return new VarTag(
            name: $tag,
            type: $type,
            variable: $variable,
            description: \trim($stream->value) !== ''
                ? $descriptions->parse(\rtrim($stream->value))
                : null,
        );
    }
}
