<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use League\Uri\Contracts\UriInterface;

/**
 * Related to external URI reference
 */
final class UriReference implements ReferenceInterface
{
    public function __construct(
        public readonly UriInterface $uri,
    ) {}
}
