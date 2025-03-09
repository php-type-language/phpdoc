<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Description;

use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;

interface DescriptionParserInterface
{
    /**
     * Returns a parsed description for the given description string.
     *
     * ```php
     * $description = $parser->parse(<<<'DOC'
     *      This is a description with {@​link Example}.
     *      DOC);
     *
     * // $description MAY contains:
     * // object(Description) {
     * //    template: "This is a description with {%s}.",
     * //    tags: [
     * //        object(LinkTag) { name: "link", ... },
     * //    ]
     * // }
     * ```
     */
    public function parse(string $description): DescriptionInterface;
}
