<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\SeeTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\TypeElementReference;
use TypeLang\PHPDoc\Parser\Content\ElementReferenceReader;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
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
            try {
                $reference = $stream->apply(new ElementReferenceReader());
            } catch (\Throwable) {
                $reference = new TypeElementReference(
                    type: $stream->apply(new TypeReader($this->parser))
                );
            }
        }

        return new SeeTag(
            name: $tag,
            ref: $reference,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
