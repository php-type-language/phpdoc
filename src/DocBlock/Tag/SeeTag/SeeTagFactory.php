<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\SeeTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\ElementReferenceReader;
use TypeLang\PHPDoc\Parser\Content\UriReferenceReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@see`" tags.
 *
 * See {@see SeeTag} for details about this tag.
 */
final class SeeTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): SeeTag
    {
        $stream = new Stream($tag, $content);

        try {
            $reference = $stream->apply(new UriReferenceReader());
        } catch (\Throwable) {
            $reference = $stream->apply(new ElementReferenceReader($this->parser));
        }

        return new SeeTag(
            name: $tag,
            ref: $reference,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
