<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class RegexPHPStanLikeBench extends FullQualifiedBenchCase
{
    private const PCRE = '/^(?:[\\]?+[a-zA-Z\x80-\xFF_][0-9a-zA-Z\x80-\xFF_-]*+)++/u';

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            \preg_match(self::PCRE, $sample, $__result);
        }
    }
}
