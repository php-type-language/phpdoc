<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use Phplrt\Lexer\Lexer;
use TypeLang\Parser\Parser;

#[Revs(10), Warmup(5), Iterations(100)]
final class TypeLangLexerBench extends FullQualifiedBenchCase
{
    private readonly Lexer $lexer;

    public function __construct()
    {
        $parser = new Parser();

        $this->lexer = (new \ReflectionClass(Parser::class))
            ->getProperty('lexer')
            ->getValue($parser);
    }

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            foreach ($this->lexer->lex($sample) as $token) {
                $isValidToken = $token->getName() === 'T_NAME'
                    || $token->getName() === 'T_NAME_WITH_SPACE'
                    || $token->getName() === 'T_NS_DELIMITER';

                if (!$isValidToken) {
                    break;
                }
            }
        }
    }
}
