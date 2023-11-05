<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use JetBrains\PhpStorm\Language;

interface DocBlockFactoryInterface
{
    /**
     * @param string $docblock A string containing the DocBlock to parse.
     */
    public function create(#[Language('PHP')] string $docblock): DocBlock;
}
