<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reader;

/**
 * @template TValue of mixed
 */
final class Sequence
{
    /**
     * @param TValue $data
     * @param int<0, max> $offset
     */
    public function __construct(
        public readonly mixed $data,
        public readonly int $offset = 0,
    ) {}
}
