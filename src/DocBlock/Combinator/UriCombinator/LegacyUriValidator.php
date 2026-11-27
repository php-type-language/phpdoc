<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Combinator\UriCombinator;

/**
 * Validates a URI by {@see \parse_url()} and checks its parts by RFC 3986,
 * for the versions of PHP before 8.5.
 *
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal TypeLang\PhpDoc\DocBlock\Combinator
 */
final class LegacyUriValidator implements UriValidatorInterface
{
    private const SCHEME = '~^[A-Za-z][A-Za-z0-9+.\-]*$~';

    /**
     * A registered name of a host.
     */
    private const HOST = '~^(?:[A-Za-z0-9\-._\~!$&\'()*+,;=]|%[0-9A-Fa-f]{2})*$~';

    /**
     * A user info, a path, a query or a fragment.
     */
    private const COMPONENT = '~^(?:[A-Za-z0-9\-._\~!$&\'()*+,;=:@/?]|%[0-9A-Fa-f]{2})*$~';

    public function isValid(string $uri): bool
    {
        $parts = \parse_url($uri);

        if ($parts === false) {
            return false;
        }

        $scheme = $parts['scheme'] ?? null;

        if ($scheme !== null && \preg_match(self::SCHEME, $scheme) !== 1) {
            return false;
        }

        if (isset($parts['host']) && !self::isHost($parts['host'])) {
            return false;
        }

        // The first segment of a path without a scheme has no colon
        if ($scheme === null && \str_contains(\explode('/', $parts['path'] ?? '', 2)[0], ':')) {
            return false;
        }

        foreach (['user', 'pass', 'path', 'query', 'fragment'] as $name) {
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
