<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class FullTokenizerBench extends FullQualifiedBenchCase
{
    private const T_FQN = '(?:[\\]?+[a-zA-Z\x80-\xFF_][0-9a-zA-Z\x80-\xFF_-]*+)++';
    private const T_IDENTIFIER = '[a-zA-Z_\\x80-\\xff][a-zA-Z0-9\\-_\\x80-\\xff]*+';

    private const SIMPLE_TOKENIZER_PCRE = '/\\G(?'
        . '|(?:(?:'. self::T_FQN . '::\\$' . self::T_IDENTIFIER . ')(*MARK:T_PROPERTY))'
        . '|(?:(?:'. self::T_FQN . '::' . self::T_IDENTIFIER . '\(\))(*MARK:T_METHOD))'
        . '|(?:(?:'. self::T_FQN . '::' . self::T_IDENTIFIER . ')(*MARK:T_CONSTANT))'
        . '|(?:(?:\\$'. self::T_IDENTIFIER . ')(*MARK:T_VARIABLE))'
        . '|(?:(?:'. self::T_FQN . '\(\))(*MARK:T_FUNCTION))'
        . '|(?:(?:'. self::T_FQN . ')(*MARK:T_IDENTIFIER))'
    . ')/Ssum';

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            \preg_match_all(self::SIMPLE_TOKENIZER_PCRE, $sample, $matches);

            if ($matches[0] !== []) {
                $__result = $matches[0][0];
            }
        }
    }
}
