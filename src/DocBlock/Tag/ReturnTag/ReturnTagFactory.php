<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ReturnTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@return`" tags.
 *
 * See {@see ReturnTag} for details about this tag.
 */
final class ReturnTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): ReturnTag
    {
        $stream = new Stream($tag, $content);

        return new ReturnTag(
            name: $tag,
            type: $stream->apply(new TypeReader($this->parser)),
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
