<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\DocBlockParser;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

#[Revs(1), Warmup(1), Iterations(2)]
final class PHPDocumentorDocBlockParserBench extends DocBlockParserBenchCase
{
    private readonly DocBlockFactoryInterface $parser;

    public function __construct()
    {
        parent::__construct();

        $this->parser = DocBlockFactory::createInstance();
    }

    public function benchDocBlockParsing(): void
    {
        foreach ($this->samples as $sample) {
            $this->parser->create($sample);
        }
    }
}
