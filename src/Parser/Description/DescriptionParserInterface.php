<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Description;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\Exception\ParsingExceptionInterface;

interface DescriptionParserInterface
{
    /**
     * @throws ParsingExceptionInterface
     */
    public function tryParse(string $description): ?DescriptionInterface;

    /**
     * @throws ParsingExceptionInterface
     */
    public function parse(string $description): DescriptionInterface;
}
