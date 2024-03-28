<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use JetBrains\PhpStorm\Language;

/**
 * @psalm-suppress UndefinedAttributeClass : JetBrains language attribute may not be available
 */
interface ParserInterface
{
    /**
     * @param string $docblock A string containing the DocBlock to parse.
     */
    public function parse(#[Language('PHP')] string $docblock): DocBlock;
}
