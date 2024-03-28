<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Tag;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\TagInterface;

interface TagParserInterface
{
    /**
     * Returns concrete tag instance by the tag signature.
     *
     * ```php
     * $tag = $parser->parse('@param string $tag');
     *
     * // $tag may contains:
     * // object(ParamTag) {
     * //    name: "param",
     * //    variable: "$tag",
     * //    type: object(NamedType<string>),
     * //    description: object(Description) { ... },
     * // }
     * ```
     */
    public function parse(string $tag, DescriptionParserInterface $parser = null): TagInterface;
}
