<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\TemplateTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Content\IdentifierReader;
use TypeLang\PHPDoc\DocBlock\Content\OptionalTypeParserReader;
use TypeLang\PHPDoc\DocBlock\Content\OptionalValueReader;
use TypeLang\PHPDoc\DocBlock\Content\Stream;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@template`" tags.
 *
 * See {@see TemplateTag} for details about this tag.
 */
final class TemplateTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): TemplateTag
    {
        $stream = new Stream($content);

        $template = $stream->apply(new IdentifierReader($tag));

        $type = null;

        $stream->lookahead(function (Stream $stream) use (&$type): bool {
            if ($stream->apply(new OptionalValueReader('of')) !== null) {
                $type = $stream->apply(new OptionalTypeParserReader($this->parser));
            }

            return $type !== null;
        });

        return new TemplateTag(
            name: $tag,
            template: $template,
            type: $type,
            description: \trim($stream->value) !== ''
                ? $descriptions->parse(\rtrim($stream->value))
                : null,
        );
    }
}
