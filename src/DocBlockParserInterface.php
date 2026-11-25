<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\PhpDoc\DocBlock\DocBlock;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;

interface DocBlockParserInterface
{
    /**
     * @param string $docblock a string containing the DocBlock to parse
     * @throws PhpDocExceptionInterface in case of the docblock cannot be parsed
     */
    public function parse(#[Language('InjectablePHP')] string $docblock): DocBlock;
}
