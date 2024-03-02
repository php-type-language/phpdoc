<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Definition;

use TypeLang\PHPDoc\Tag\TagInterface;

/**
 * @template TTag of TagInterface
 */
interface ParsableInterface
{
    /**
     * @param non-empty-string $name
     *
     * @return TTag
     */
    public function parse(string $name, string $payload): TagInterface;
}
