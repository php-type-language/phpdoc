<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\OverrideTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@override`" tags.
 *
 * See {@see OverrideTag} for details about this tag.
 */
final class OverrideTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): OverrideTag
    {
        $stream = new Stream($tag, $content);

        return new OverrideTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
