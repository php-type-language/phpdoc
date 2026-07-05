<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser;

use TypeLang\PhpDoc\Parser\Splitter\Segment;

final readonly class RawDocBlock
{
    public function __construct(
        public Segment $description,
        /**
         * @var list<Segment>
         */
        public array $tags,
    ) {}
}
