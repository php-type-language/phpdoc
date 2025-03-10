<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

/**
 * @template T of mixed
 */
abstract class Reader
{
    /**
     * @return T
     */
    abstract public function __invoke(Stream $stream): mixed;
}
