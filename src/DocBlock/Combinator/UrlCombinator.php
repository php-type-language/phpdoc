<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\UrlReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;

/**
 * Reads a URL, that is a word carrying a scheme.
 *
 * A word without a scheme is not a URL and is left for the following
 * combinators.
 *
 * @template-implements CombinatorInterface<UrlReference>
 */
final readonly class UrlCombinator implements CombinatorInterface
{
    public const string NAME = 'URL';

    public function __invoke(Cursor $cursor): UrlReference
    {
        $word = $cursor->readWord();

        if ($word === '' || !self::isUrl($word)) {
            throw new NoMatchException('Expected a URL');
        }

        return new UrlReference($word);
    }

    private static function isUrl(string $word): bool
    {
        if (\PHP_VERSION_ID >= 80500) {
            return \Uri\WhatWg\Url::parse($word) !== null;
        }

        return \is_string(\parse_url($word, \PHP_URL_SCHEME));
    }
}
