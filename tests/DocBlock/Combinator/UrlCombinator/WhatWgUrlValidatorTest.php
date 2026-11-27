<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UrlCombinator;

use PHPUnit\Framework\Attributes\RequiresPhp;
use TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator\WhatWgUrlValidator;

#[RequiresPhp('>= 8.5.0')]
final class WhatWgUrlValidatorTest extends UrlValidatorTestCase
{
    protected function createValidator(): WhatWgUrlValidator
    {
        return new WhatWgUrlValidator();
    }
}
