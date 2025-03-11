<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\DocBlockParser;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use TypeLang\PHPDoc\Parser;

#[Revs(1), Warmup(1), Iterations(2)]
final class TypeLangDocBlockParserBench extends DocBlockParserBenchCase
{
    private readonly Parser $parser;

    public function __construct()
    {
        parent::__construct();

        $this->parser = new Parser();
    }

    public function benchDocBlockParsing(): void
    {
        foreach ($this->samples as $sample) {
            $this->parser->parse($sample);
        }
    }
}
