<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\Splitter;

/**
 * @readonly This class is read-only, but is marked with an ANNOTATION instead
 *           of a native modifier. This is required to allow object creation
 *           via `clone`, which speeds up object instantiation by bypassing
 *           the constructor call.
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
