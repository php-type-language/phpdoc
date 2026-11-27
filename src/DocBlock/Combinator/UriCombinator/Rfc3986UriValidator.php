<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;

use Uri\Rfc3986\Uri;

/**
 * Validates a URI by the RFC 3986 parser of PHP 8.5.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
final class Rfc3986UriValidator implements UriValidatorInterface
{
    public function isValid(string $uri): bool
    {
        return Uri::parse($uri) !== null;
    }
}
