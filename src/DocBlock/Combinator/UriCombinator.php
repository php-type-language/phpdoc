<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator;

use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\Parser\Grammar\CombinatorInterface;
use TypeLang\PhpDoc\Parser\Grammar\Cursor;
use TypeLang\PhpDoc\Parser\Grammar\Exception\NoMatchException;
use Uri\Rfc3986\Uri;

/**
 * Reads a URI, that is a well-formed word as defined by RFC 3986.
 *
 * @template-implements CombinatorInterface<UriReference>
 */
final readonly class UriCombinator implements CombinatorInterface
{
    public const string NAME = 'URI';

    public function __invoke(Cursor $cursor): UriReference
    {
        $uri = $cursor->readWord();

        if ($uri === '' || !self::isUri($uri)) {
            throw new NoMatchException('Expected a URI');
        }

        return new UriReference($uri);
    }

    private static function isUriEmpty(?Uri $uri): bool
    {
        if ($uri === null) {
            return true;
        }

        return \trim($uri->toRawString(), '/?#') === '';
    }

    private static function isUri(string $word): bool
    {
        if (\PHP_VERSION_ID >= 80500) {
            return !self::isUriEmpty(Uri::parse($word));
        }

        return \parse_url($word) !== false;
    }
}
