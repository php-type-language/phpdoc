<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use TypeLang\Parser\Parser;

#[Revs(10), Warmup(5), Iterations(100)]
final class TypeLangParserBench extends FullQualifiedBenchCase
{
    private readonly Parser $parser;

    public function __construct()
    {
        $this->parser = new Parser(tolerant: true);
    }

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            $__result = $this->parser->parse($sample);
        }
    }
}
