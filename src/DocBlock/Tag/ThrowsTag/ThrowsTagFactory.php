<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ThrowsTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeParserReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@throws`" tags.
 *
 * See {@see ThrowsTag} for details about this tag.
 */
final class ThrowsTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): ThrowsTag
    {
        $stream = new Stream($tag, $content);

        return new ThrowsTag(
            name: $tag,
            type: $stream->apply(new TypeParserReader($this->parser)),
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
