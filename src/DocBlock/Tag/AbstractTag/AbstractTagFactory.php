<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\AbstractTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@abstract`" tags.
 *
 * See {@see AbstractTag} for details about this tag.
 */
final class AbstractTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): AbstractTag
    {
        $stream = new Stream($tag, $content);

        return new AbstractTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
