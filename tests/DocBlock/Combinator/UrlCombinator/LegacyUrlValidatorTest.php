<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UrlCombinator;

use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\LegacyUrlValidator;

final class LegacyUrlValidatorTest extends UrlValidatorTestCase
{
    protected function createValidator(): LegacyUrlValidator
    {
        return new LegacyUrlValidator();
    }
}
