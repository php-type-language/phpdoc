<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Comment;

final class Segment implements \Stringable
{
    /**
     * @param int<0, max> $offset
     */
    public function __construct(
        public string $text = '',
        public int $offset = 0,
    ) {}

    public function __toString(): string
    {
        return $this->text;
    }
}
