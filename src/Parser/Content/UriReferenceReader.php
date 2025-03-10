<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Parser\Content;

use League\Uri\Uri;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\UriReference;

/**
 * A strict URI parser that requires an "scheme://" or "//" prefix for a
 * string to be recognized as a valid Uri.
 *
 * This analysis can be used as part of internal and external link recognition.
 *
 * @template-implements ReaderInterface<UriReference>
 */
final class UriReferenceReader implements ReaderInterface
{
    /**
     * Scheme (e)BNF defined in RFC 2396
     *
     * ```
     * alpha    = lowalpha | upalpha
     *
     * lowalpha = "a" | "b" | "c" | "d" | "e" | "f" | "g" | "h" | "i" |
     *            "j" | "k" | "l" | "m" | "n" | "o" | "p" | "q" | "r" |
     *            "s" | "t" | "u" | "v" | "w" | "x" | "y" | "z"
     *
     * upalpha  = "A" | "B" | "C" | "D" | "E" | "F" | "G" | "H" | "I" |
     *            "J" | "K" | "L" | "M" | "N" | "O" | "P" | "Q" | "R" |
     *            "S" | "T" | "U" | "V" | "W" | "X" | "Y" | "Z"
     *
     * scheme   = alpha *( alpha | digit | '+' | '-' | '.' )
     * ```
     */
    private const OPTIONAL_SCHEME_STARTED_PCRE = '/^([a-z][a-z0-9+\-.]*:)?\/\/\S+/ui';

    public function __invoke(Stream $stream): UriReference
    {
        \preg_match(self::OPTIONAL_SCHEME_STARTED_PCRE, $stream->value, $matches);

        if ($matches === []) {
            throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect URI "%s"',
                $stream->tag,
                $stream->value,
            ));
        }

        try {
            $uri = Uri::new($matches[0] ?? '');
        } catch (\Throwable $e) {
            throw $stream->toException(\sprintf(
                'Tag @%s contains an incorrect URI "%s"',
                $stream->tag,
                $matches[0] ?? '',
            ), $e);
        }

        $stream->shift(\strlen($matches[0]));

        return new UriReference($uri);
    }
}
