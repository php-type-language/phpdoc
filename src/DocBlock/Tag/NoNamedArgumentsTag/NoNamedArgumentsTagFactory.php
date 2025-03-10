<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\NoNamedArgumentsTag;

use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@no-named-arguments`" tags.
 *
 * See {@see NoNamedArgumentsTag} for details about this tag.
 */
final class NoNamedArgumentsTagFactory implements TagFactoryInterface
{
    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): NoNamedArgumentsTag
    {
        $stream = new Stream($tag, $content);

        return new NoNamedArgumentsTag(
            name: $tag,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
