<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ParamTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
use TypeLang\PHPDoc\Parser\Content\VariableNameReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@param`" tags.
 *
 * See {@see ParamTag} for details about this tag.
 */
final class ParamTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    private function isVariable(string $content): bool
    {
        return \str_starts_with($content, '&$')
            || \str_starts_with($content, '...$')
            || \str_starts_with($content, '&...$')
            || \str_starts_with($content, '$');
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): ParamTag
    {
        $stream = new Stream($tag, $content);

        $type = null;
        $output = $variadic = false;

        if (!$this->isVariable($stream->value)) {
            $type = $stream->apply(new TypeReader($this->parser));
        }

        if (\str_starts_with($stream->value, '&')) {
            $stream->shift(1);
            $output = true;
        }

        if (\str_starts_with($stream->value, '...')) {
            $stream->shift(3);
            $variadic = true;
        }

        $variable = $stream->apply(new VariableNameReader());

        return new ParamTag(
            name: $tag,
            type: $type,
            variable: $variable,
            isVariadic: $variadic,
            isOutput: $output,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
