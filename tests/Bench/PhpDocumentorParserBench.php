<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\RetryThreshold;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

#[Revs(20), Warmup(5), Iterations(15), BeforeMethods('prepare'), RetryThreshold(2)]
final readonly class PhpDocumentorParserBench extends DocBlockParserBench
{
    private DocBlockFactoryInterface $parser;

    public function prepare(): void
    {
        $this->parser = DocBlockFactory::createInstance();
    }

    public function benchParseDocBlock(): void
    {
        $this->parser->create(self::DOC_BLOCK_SAMPLE);
    }
}
