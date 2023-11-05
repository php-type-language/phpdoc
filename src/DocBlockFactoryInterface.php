<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser;

use JetBrains\PhpStorm\Language;

/**
 * @psalm-suppress UndefinedAttributeClass : JetBrains language attribute may not be available
 */
interface DocBlockFactoryInterface
{
    /**
     * @param string $docblock A string containing the DocBlock to parse.
     */
    public function create(#[Language('PHP')] string $docblock): DocBlock;
}
