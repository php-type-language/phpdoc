<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UriCombinator;

use PHPUnit\Framework\Attributes\RequiresPhp;
use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\Rfc3986UriValidator;

#[RequiresPhp('>= 8.5.0')]
final class Rfc3986UriValidatorTest extends UriValidatorTestCase
{
    protected function createValidator(): Rfc3986UriValidator
    {
        return new Rfc3986UriValidator();
    }
}
