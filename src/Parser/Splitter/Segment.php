<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc
 *
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
