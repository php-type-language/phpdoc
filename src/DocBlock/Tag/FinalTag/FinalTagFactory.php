<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\FinalTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@final`" tags.
 *
 * See {@see FinalTag} for details about this tag.
 */
final class FinalTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): FinalTag
    {
        $stream = new Stream($tag, $content);

        return new FinalTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
