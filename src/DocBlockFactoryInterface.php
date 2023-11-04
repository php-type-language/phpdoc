<?php

declare(strict_types=1);

namespace TypeLang\Reader;

interface DocBlockFactoryInterface
{
    /**
     * @param string $docblock A string containing the DocBlock to parse.
     */
    public function create(string $docblock): DocBlock;
}
