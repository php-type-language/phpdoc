<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\RetryThreshold;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use TypeLang\PhpDoc\DocBlockParser;
use TypeLang\PhpDoc\DocBlockParserInterface;

#[Revs(20), Warmup(5), Iterations(15), BeforeMethods('prepare'), RetryThreshold(2)]
final readonly class TypeLangParserBench extends DocBlockParserBench
{
    private DocBlockParserInterface $parser;

    public function prepare(): void
    {
        $this->parser = new DocBlockParser();
    }

    public function benchParseDocBlock(): void
    {
        $this->parser->parse(self::DOC_BLOCK_SAMPLE);
    }
}
