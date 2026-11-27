<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Tests\DocBlock\Combinator\UriCombinator;

use TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator\LegacyUriValidator;

final class LegacyUriValidatorTest extends UriValidatorTestCase
{
    protected function createValidator(): LegacyUriValidator
    {
        return new LegacyUriValidator();
    }
}
