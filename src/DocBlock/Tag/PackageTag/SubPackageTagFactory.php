<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PackageTag;

use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Content\Stream;
use TypeLang\PHPDoc\Parser\Content\TypeReader;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@subpackage`" tags.
 *
 * See {@see SubPackageTag} for details about this tag.
 */
final class SubPackageTagFactory implements TagFactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): SubPackageTag
    {
        $stream = new Stream($tag, $content);

        $type = $stream->apply(new TypeReader($this->parser));

        if (!$type instanceof NamedTypeNode) {
            throw $stream->toException(\sprintf(
                'Tag @%s expects the namespace to be defined',
                $stream->tag,
            ));
        }

        if ($type->arguments !== null) {
            throw $stream->toException(\sprintf(
                'Tag @%s namespace cannot contain template arguments',
                $stream->tag,
            ));
        }

        if ($type->fields !== null) {
            throw $stream->toException(\sprintf(
                'Tag @%s namespace cannot contain shape fields',
                $stream->tag,
            ));
        }

        return new SubPackageTag(
            name: $tag,
            package: $type->name,
            description: $stream->toOptionalDescription($descriptions),
        );
    }
}
