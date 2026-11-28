<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\Parser\Splitter\Segment;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc
 */
final class RawDocBlock
{
    public function __construct(
        public readonly ?Segment $description,
        /**
         * @var list<Segment>
         */
        public readonly array $tags,
    ) {}
}
