<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use Psr\Http\Message\UriInterface;

/**
 * Representation of the "https://example.com/path/to/file" like format.
 */
final class UriReference extends Reference implements UriInterface
{
    private ?string $uri = null;

    private ?string $authority = null;

    /**
     * @param string $scheme Uri scheme (without '://' suffix)
     * @param string $host Uri host.
     * @param int<1, 65535> |null $port Uri port number.
     * @param non-empty-string $path Uri path.
     * @param string $query Uri query string (without '?' prefix)
     * @param string $fragment Uri fragment string (without '#' prefix)
     * @param string $user Uri user.
     * @param string $password Uri password.
     */
    public function __construct(
        private readonly string $scheme,
        private readonly string $host = '',
        private readonly ?int $port = null,
        private readonly string $path = '/',
        private readonly string $query = '',
        private readonly string $fragment = '',
        private readonly string $user = '',
        private readonly string $password = ''
    ) {}

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function withScheme($scheme): self
    {
        assert(\is_string($scheme), new \TypeError(
            message: \vsprintf('Argument #1 ($scheme) must be of type string, %s given', [
                \get_debug_type($scheme),
            ])
        ));

        return new self(
            scheme: $scheme,
            host: $this->host,
            port: $this->port,
            path: $this->path,
            query: $this->query,
            fragment: $this->fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getUserInfo(): string
    {
        $info = $this->user;

        if ($this->password !== '') {
            $info .= ':' . $this->password;
        }

        return $info;
    }

    public function withUserInfo($user, $password = null): self
    {
        assert(\is_string($user), new \TypeError(
            message: \vsprintf('Argument #1 ($user) must be of type string, %s given', [
                \get_debug_type($user),
            ])
        ));

        assert(\is_string($password) || $password === null, new \TypeError(
            message: \vsprintf('Argument #2 ($password) must be of type string|null, %s given', [
                \get_debug_type($user),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            path: $this->path,
            query: $this->query,
            fragment: $this->fragment,
            user: $user,
            password: $password ?? $this->password,
        );
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost($host): self
    {
        assert(\is_string($host), new \TypeError(
            message: \vsprintf('Argument #1 ($host) must be of type string, %s given', [
                \get_debug_type($host),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $host,
            port: $this->port,
            path: $this->path,
            query: $this->query,
            fragment: $this->fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function withPort($port): self
    {
        assert(\is_int($port) || $port === null, new \TypeError(
            message: \vsprintf('Argument #1 ($port) must be of type int|null, %s given', [
                \get_debug_type($port),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $this->host,
            port: $port,
            path: $this->path,
            query: $this->query,
            fragment: $this->fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function withPath($path): self
    {
        assert(\is_string($path), new \TypeError(
            message: \vsprintf('Argument #1 ($path) must be of type string, %s given', [
                \get_debug_type($path),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            path: $path,
            query: $this->query,
            fragment: $this->fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function withQuery($query): self
    {
        assert(\is_string($query), new \TypeError(
            message: \vsprintf('Argument #1 ($query) must be of type string, %s given', [
                \get_debug_type($query),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            path: $this->path,
            query: $query,
            fragment: $this->fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withFragment($fragment): self
    {
        assert(\is_string($fragment), new \TypeError(
            message: \vsprintf('Argument #1 ($fragment) must be of type string, %s given', [
                \get_debug_type($fragment),
            ])
        ));

        return new self(
            scheme: $this->scheme,
            host: $this->host,
            port: $this->port,
            path: $this->path,
            query: $this->query,
            fragment: $fragment,
            user: $this->user,
            password: $this->password,
        );
    }

    public function getAuthority(): string
    {
        if ($this->authority !== null) {
            return $this->authority;
        }

        $this->authority = $this->getHost();

        if ($this->user !== '') {
            $this->authority = "{$this->user}@{$this->authority}";
        }

        if ($this->port !== null) {
            $this->authority .= ":{$this->port}";
        }

        return $this->authority;
    }

    /**
     * @return array{
     *     kind: int<0, max>,
     *     uri: non-empty-string
     * }
     */
    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'kind' => ReferenceKind::NAME_KIND,
            'uri' => (string)$this,
        ];
    }

    public function __toString(): string
    {
        if ($this->uri !== null) {
            return $this->uri;
        }

        $this->uri = '';

        if ($this->scheme !== '') {
            $this->uri .= "{$this->scheme}:";
        }

        if (($authority = $this->getAuthority()) !== '') {
            $this->uri .= "//{$authority}";
        }

        $this->uri .= '' !== $this->path && !\str_starts_with($this->path, '/')
            ? "/{$this->path}"
            : $this->path;

        if ($this->query !== '') {
            $this->uri .= "?{$this->query}";
        }

        if ($this->fragment !== '') {
            $this->uri .= "#{$this->fragment}";
        }

        return $this->uri;
    }
}
