<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;

/**
 * Tells whether a word is a URI reference as defined by RFC 3986.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
interface UriValidatorInterface
{
    public function isValid(string $uri): bool;
}
