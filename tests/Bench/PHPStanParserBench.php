<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\RetryThreshold;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;

#[Revs(20), Warmup(5), Iterations(15), BeforeMethods('prepare'), RetryThreshold(2)]
final readonly class PHPStanParserBench extends DocBlockParserBench
{
    private Lexer $lexer;
    private PhpDocParser $parser;

    public function prepare(): void
    {
        $config = new ParserConfig(usedAttributes: [
            'lines' => true,
            'indexes' => true,
            'comments' => true,
        ]);

        $this->lexer = new Lexer($config);

        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->parser = new PhpDocParser($config, $typeParser, $constExprParser);
    }

    public function benchParseDocBlock(): void
    {
        $iterator = new TokenIterator($this->lexer->tokenize(self::DOC_BLOCK_SAMPLE));

        $this->parser->parse($iterator);
    }
}
