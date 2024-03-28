<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser;

final class SourceMap
{
    /**
     * @var array<int<0, max>, int<0, max>>
     */
    private array $mappings = [];

    /**
     * @var int<0, max>
     */
    private int $offset = 0;

    /**
     * @var int<0, max>
     */
    private int $max = 0;

    /**
     * @param int<0, max> $offset
     */
    public function add(int $offset, string $segment): void
    {
        $this->mappings[$this->offset] = $offset;

        $length = \strlen($segment);

        $this->max = \max($this->max, $offset + $length - 1);
        $this->offset += $length;
    }

    /**
     * @return int<0, max>
     */
    public function getOffset(int $offset): int
    {
        if ($offset >= $this->offset) {
            return $this->max;
        }

        $result = 0;

        foreach ($this->mappings as $from => $to) {
            if ($from > $offset) {
                return \max(0, $result + $offset);
            }

            $result = $to - $from;
        }

        return \max(0, $result + $offset);
    }
}
