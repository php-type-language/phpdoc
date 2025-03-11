<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\CopyrightTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@copyright`" tags.
 *
 * See {@see CopyrightTag} for details about this tag.
 */
final class CopyrightTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): CopyrightTag
    {
        $stream = new Stream($tag, $content);

        return new CopyrightTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
