<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use JetBrains\PhpStorm\Language;

interface ParserInterface
{
    /**
     * @param string $docblock A string containing the DocBlock to parse.
     */
    public function parse(#[Language('PHP')] string $docblock): DocBlock;
}
