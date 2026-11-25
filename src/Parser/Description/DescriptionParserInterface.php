<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Description;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;

interface DescriptionParserInterface
{
    /**
     * @throws ParsingExceptionInterface in case of an inline tag cannot be
     *        parsed
     */
    public function tryParse(string $description): ?DescriptionInterface;

    /**
     * @throws ParsingExceptionInterface in case of an inline tag cannot be
     *        parsed
     */
    public function parse(string $description): DescriptionInterface;
}
