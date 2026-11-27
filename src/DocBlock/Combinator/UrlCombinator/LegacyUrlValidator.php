<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UrlCombinator;

/**
 * Validates a URL by {@see \parse_url()} and checks its parts by WHATWG,
 * for the versions of PHP before 8.5.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
final class LegacyUrlValidator implements UrlValidatorInterface
{
    private const SCHEME = '~^[A-Za-z][A-Za-z0-9+.\-]*$~';

    /**
     * A domain of a host, with characters beyond ASCII.
     */
    private const HOST = '~^(?:[A-Za-z0-9\-._\~!$&\'()*+,;=\x80-\xFF]|%[0-9A-Fa-f]{2})+$~';

    /**
     * A path, a query or a fragment, with characters beyond ASCII.
     */
    private const COMPONENT = '~^(?:[A-Za-z0-9\-._\~!$&\'()*+,;=:@/?\x80-\xFF]|%[0-9A-Fa-f]{2})*$~';

    public function isValid(string $url): bool
    {
        $parts = \parse_url($url);

        if ($parts === false) {
            return false;
        }

        if (\preg_match(self::SCHEME, $parts['scheme'] ?? '') !== 1) {
            return false;
        }

        if (!self::isHost($parts['host'] ?? '')) {
            return false;
        }

        // The credentials are a validation error of WHATWG
        if (isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        foreach (['path', 'query', 'fragment'] as $name) {
            if (\preg_match(self::COMPONENT, $parts[$name] ?? '') !== 1) {
                return false;
            }
        }

        return true;
    }

    private static function isHost(string $host): bool
    {
        if (\str_starts_with($host, '[') && \str_ends_with($host, ']')) {
            return \filter_var(\substr($host, 1, -1), \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) !== false;
        }

        return \preg_match(self::HOST, $host) === 1;
    }
}
