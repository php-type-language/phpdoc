<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\MethodTag;

use TypeLang\Parser\Node\Stmt\CallableTypeNode;
use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\OptionalTypeReader;
use TypeLang\PHPDoc\Parser\Content\OptionalValueReader;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@method`" tags.
 *
 * See {@see MethodTag} for details about this tag.
 */
final class MethodTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): MethodTag
    {
        $stream = new Stream($tag, $content);

        $isStatic = $stream->apply(new OptionalValueReader('static')) !== null;
        $returnType = $stream->apply(new TypeReader($this->parser));
        $callableType = $stream->apply(new OptionalTypeReader($this->parser));

        // In case of return type has not been defined then we swap first
        // defined type as method signature definition.
        if ($callableType === null) {
            $callableType = $returnType;
            $returnType = null;
        }

        if (!$callableType instanceof CallableTypeNode) {
            throw $stream->toException(
                message: \sprintf(
                    'The @%s annotation must contain the method signature',
                    $tag,
                ),
            );
        }

        if ($callableType->type !== null && $returnType !== null) {
            throw $stream->toException(
                message: \sprintf('You can specify the return type of '
                    . 'a method of the @%s annotation before or after the '
                    . 'method`s signature, but not both', $tag),
            );
        }

        $callableType->type ??= $returnType;

        return new MethodTag(
            name: $tag,
            type: $callableType,
            static: $isStatic,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
