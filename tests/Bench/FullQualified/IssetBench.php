<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class IssetBench extends FullQualifiedBenchCase
{
    public const CHARSET = [
        '_' => true,
        '-' => true,
        '\\' => true,
        'A' => true,
        'B' => true,
        'C' => true,
        'D' => true,
        'E' => true,
        'F' => true,
        'G' => true,
        'H' => true,
        'I' => true,
        'J' => true,
        'K' => true,
        'L' => true,
        'M' => true,
        'N' => true,
        'O' => true,
        'P' => true,
        'Q' => true,
        'R' => true,
        'S' => true,
        'T' => true,
        'U' => true,
        'V' => true,
        'W' => true,
        'X' => true,
        'Y' => true,
        'Z' => true,
        'a' => true,
        'b' => true,
        'c' => true,
        'd' => true,
        'e' => true,
        'f' => true,
        'g' => true,
        'h' => true,
        'i' => true,
        'j' => true,
        'k' => true,
        'l' => true,
        'm' => true,
        'n' => true,
        'o' => true,
        'p' => true,
        'q' => true,
        'r' => true,
        's' => true,
        't' => true,
        'u' => true,
        'v' => true,
        'w' => true,
        'x' => true,
        'y' => true,
        'z' => true,
        '0' => true,
        '1' => true,
        '2' => true,
        '3' => true,
        '4' => true,
        '5' => true,
        '6' => true,
        '7' => true,
        '8' => true,
        '9' => true,
    ];

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            for ($offset = 0, $length = \strlen($sample); $offset < $length; $offset++) {
                if (!isset(self::CHARSET[$sample[$offset]])) {
                    break;
                }
            }

            $__result = \substr($sample, 0, $offset);
        }
    }
}
