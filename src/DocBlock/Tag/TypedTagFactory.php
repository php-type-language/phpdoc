<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\Parser\Parser;
use TypeLang\PhpDocParser\DocBlock\DescriptionFactoryInterface;
use TypeLang\PhpDocParser\DocBlock\Extractor\TagTypeExtractor;

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
