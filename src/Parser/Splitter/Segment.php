<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

/**
 * @readonly
 */
final class Segment
{
    public function __construct(
        /**
         * @var non-empty-string
         */
        public string $text,
        /**
         * @var int<0, max>
         */
        public int $offset = 0,
    ) {}
}
