<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\Description;

use TypeLang\PhpDocParser\DocBlock\Description;

interface DescriptionFactoryInterface
{
    /**
     * Returns the parsed text of this description.
     */
    public function create(string $contents): Description;
}
