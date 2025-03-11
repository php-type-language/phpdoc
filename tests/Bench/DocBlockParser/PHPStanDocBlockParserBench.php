<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\DocBlockParser;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;

#[Revs(1), Warmup(1), Iterations(2)]
final class PHPStanDocBlockParserBench extends DocBlockParserBenchCase
{
    private readonly Lexer $lexer;
    private readonly PhpDocParser $parser;

    public function __construct()
    {
        parent::__construct();

        $config = new ParserConfig(usedAttributes: [
            'lines' => true,
            'indexes' => true,
            'comments' => true,
        ]);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->lexer = new Lexer($config);
        $this->parser = new PhpDocParser($config, $typeParser, $constExprParser);
    }

    public function benchDocBlockParsing(): void
    {
        foreach ($this->samples as $sample) {
            $tokens = new TokenIterator($this->lexer->tokenize($sample));
            $this->parser->parse($tokens);
        }
    }
}
