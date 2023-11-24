<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\TagFactory;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\Description\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagTypeExtractor;
use TypeLang\PhpDocParser\DocBlock\Tag\TagInterface;

/**
 * @template TReturn of TagInterface
 *
 * @template-extends TagFactory<TReturn>
 */
abstract class TypedTagFactory extends TagFactory
{
    protected readonly TagTypeExtractor $types;

    public function __construct(
        Parser $parser = new Parser(true),
        ?DescriptionFactoryInterface $descriptions = null,
    ) {
        $this->types = new TagTypeExtractor($parser);

        parent::__construct($descriptions);
    }
}
