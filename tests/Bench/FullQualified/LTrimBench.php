<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class LTrimBench extends FullQualifiedBenchCase
{
    public const CHARSET = '_-\\'
        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        . 'abcdefghijklmnopqrstuvwxyz'
        . '0123456789';

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            $length = \strlen($sample);
            $after = \strlen(\ltrim($sample, self::CHARSET));
            $__result = \substr($sample, $length, $after);
        }
    }
}
