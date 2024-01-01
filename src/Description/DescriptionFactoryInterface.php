<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Description;

use TypeLang\PhpDoc\Parser\DocBlock\Description;

interface DescriptionFactoryInterface
{
    /**
     * Returns the parsed text of this description.
     */
    public function create(string $contents): Description;
}
