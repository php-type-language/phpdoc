<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\IgnoreTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@ignore`" tags.
 *
 * See {@see IgnoreTag} for details about this tag.
 */
final class IgnoreTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): IgnoreTag
    {
        $stream = new Stream($tag, $content);

        return new IgnoreTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
