<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\TemplateTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\IdentifierReader;
use TypeLang\PHPDoc\Parser\Content\OptionalTypeReader;
use TypeLang\PHPDoc\Parser\Content\OptionalValueReader;
use TypeLang\PHPDoc\Parser\Content\Stream;
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
        $stream = new Stream($tag, $content);

        $template = $stream->apply(new IdentifierReader());

        $type = null;

        $stream->lookahead(function (Stream $stream) use (&$type): bool {
            if ($stream->apply(new OptionalValueReader('of')) !== null) {
                $type = $stream->apply(new OptionalTypeReader($this->parser));
            }

            return $type !== null;
        });

        return new TemplateTag(
            name: $tag,
            template: $template,
            type: $type,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
