<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PackageTag;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Factory\TagFactoryInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

/**
 * This class is responsible for creating "`@package`" tags.
 *
 * See {@see PackageTag} for details about this tag.
 */
final class PackageTagFactory implements TagFactoryInterface
{
    private readonly SubPackageTagFactory $factory;

    public function __construct(
        TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        $this->factory = new SubPackageTagFactory($parser);
    }

    public function create(string $tag, string $content, DescriptionParserInterface $descriptions): PackageTag
    {
        $result = $this->factory->create($tag, $content, $descriptions);

        return new PackageTag(
            name: $result->name,
            package: $result->package,
            description: $result->description,
        );
    }
}
