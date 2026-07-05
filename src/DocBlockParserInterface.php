<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use JetBrains\PhpStorm\Language;
use TypeLang\PhpDoc\DocBlock\DocBlock;

interface DocBlockParserInterface
{
    /**
     * @param string $docblock a string containing the DocBlock to parse
     */
    public function parse(#[Language('InjectablePHP')] string $docblock): DocBlock;
}
