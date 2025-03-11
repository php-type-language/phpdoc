<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class RegexBench extends FullQualifiedBenchCase
{
    private const PCRE = '/^'
        . '\\\\?[a-zA-Z\\x80-\\xFF_][a-zA-Z0-9\\x80-\\xFF\\-_]'
        .'*(?:\\\\[a-zA-Z\\x80-\\xFF_][a-zA-Z0-9\\x80-\\xFF\\-_]*)*'
        . '/u';

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            \preg_match(self::PCRE, $sample, $__result);
        }
    }
}
