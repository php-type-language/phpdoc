<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Tag;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;

interface TagParserInterface
{
    /**
     * Parses and creates a tag instance from passed content.
     *
     * Expected `$tag` argument should be looks like:
     *  - "@tag"
     *  - "@tag with description"
     *  - "@tag With\TypeName"
     *  - "@tag With\TypeName And description"
     *  - "@tag With\TypeName $andVariableName"
     *  - "@tag With\TypeName $andVariableName And description"
     *  - etc...
     *
     * @throws ParsingExceptionInterface
     */
    public function parse(string $definition): TagInterface;
}
