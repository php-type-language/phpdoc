<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Content;

use TypeLang\PHPDoc\Tag\Content;

/**
 * @template T of mixed
 */
abstract class Applicator
{
    /**
     * @return T
     */
    abstract public function __invoke(Content $lexer): mixed;
}
