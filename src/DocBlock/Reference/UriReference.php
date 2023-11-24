<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

final class UriReference implements ReferenceInterface
{
    public function __construct(
        private readonly string $uri,
    ) {}

    public function __toString(): string
    {
        return $this->uri;
    }
}
