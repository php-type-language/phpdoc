<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reader;

/**
 * @template TValue of mixed
 */
interface ReaderInterface
{
    /**
     * @return Sequence<TValue>|null
     */
    public function read(string $content): ?Sequence;
}
