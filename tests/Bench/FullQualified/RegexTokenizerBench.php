<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class RegexTokenizerBench extends FullQualifiedBenchCase
{
    private const SIMPLE_TOKENIZER_PCRE = '/\\G(?'
        . '|(?:(?:\\$[a-zA-Z_\\x80-\\xff][a-zA-Z0-9\\-_\\x80-\\xff]*)(*MARK:T_VARIABLE))'
        . '|(?:(?:[a-zA-Z_\\x80-\\xff][a-zA-Z0-9\\-_\\x80-\\xff]*)(*MARK:T_NAME))'
        . '|(?:(?:\\()(*MARK:T_PARENTHESIS_OPEN))'
        . '|(?:(?:\\))(*MARK:T_PARENTHESIS_CLOSE))'
        . '|(?:(?:::)(*MARK:T_DOUBLE_COLON))'
        . '|(?:(?:\\\\)(*MARK:T_NS_DELIMITER))'
        . '|(?:(?:\\|)(*MARK:T_OR))'
        . '|(?:(?:(\\/\\/|#).+?$)(*MARK:T_COMMENT))'
        . '|(?:(?:\\/\\*.*?\\*\\/)(*MARK:T_DOC_COMMENT))'
        . '|(?:(?:(\\xfe\\xff|\\x20|\\x09|\\x0a|\\x0d)+)(*MARK:T_WHITESPACE))'
        . '|(?:(?:.+?)(*MARK:T_OTHER))'
    . ')/Ssum';

    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            \preg_match_all(self::SIMPLE_TOKENIZER_PCRE, $sample, $matches, \PREG_SET_ORDER);
            $__result = '';

            foreach ($matches as ['MARK' => $name, 0 => $value]) {
                if ($name === 'T_NAME' || $name === 'T_NS_DELIMITER') {
                    continue;
                }

                $__result .= $value;

                break;
            }
        }
    }
}
