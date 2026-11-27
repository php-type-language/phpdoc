<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;

/**
 * Tells whether a word is a URL with a host as defined by WHATWG.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
interface UrlValidatorInterface
{
    public function isValid(string $url): bool;
}
