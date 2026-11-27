<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;

use Uri\WhatWg\Url;

/**
 * Validates a URL by the WHATWG parser of PHP 8.5, a URL with validation
 * errors is not valid.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
final class WhatWgUrlValidator implements UrlValidatorInterface
{
    public function isValid(string $url): bool
    {
        $errors = [];
        $parsed = Url::parse($url, null, $errors);

        return $errors === [] && (string) $parsed?->getAsciiHost() !== '';
    }
}
