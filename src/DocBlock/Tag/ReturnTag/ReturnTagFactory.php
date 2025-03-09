<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ReturnTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Content\Stream;
use TypeLang\PHPDoc\DocBlock\Content\TypeParserReader;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
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
        $stream = new Stream($content);

        return new ReturnTag(
            name: $tag,
            type: $stream->apply(new TypeParserReader($tag, $this->parser)),
            description: \trim($stream->value) !== ''
                ? $descriptions->parse(\rtrim($stream->value))
                : null,
        );
    }
}
