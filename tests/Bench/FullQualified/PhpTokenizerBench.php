<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tests\Bench\FullQualified;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;

#[Revs(10), Warmup(5), Iterations(100)]
final class PhpTokenizerBench extends FullQualifiedBenchCase
{
    public function benchIdentifierReading(): void
    {
        foreach (self::PARSING_SAMPLES as $sample) {
            foreach (\PhpToken::tokenize('<?php ' . $sample) as $token) {
                if ($token->id === \T_OPEN_TAG) {
                    continue;
                }

                if ($token->id === \T_NAME_QUALIFIED) {
                    $__result = $token->text;
                    break;
                }

                break;
            }

        }
    }
}
