<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\LinkTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\TypeElementReference;
use TypeLang\PHPDoc\Parser\Content\ElementReferenceReader;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
use TypeLang\PHPDoc\Parser\Content\UriReferenceReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@link`" tags.
 *
 * See {@see LinkTag} for details about this tag.
 */
final class LinkTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): LinkTag
    {
        $stream = new Stream($tag, $content);

        try {
            $reference = $stream->apply(new UriReferenceReader());
        } catch (\Throwable) {
            $reference = $stream->apply(new ElementReferenceReader());
        }

        return new LinkTag(
            name: $tag,
            uri: $reference,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
