<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Reference;

/**
 * A reference to an external resource identified by a URI, for example
 * a web page.
 *
 * Such a reference always points outside the described codebase, so it
 * is always external.
 */
class UriReference implements ReferenceInterface
{
    public readonly bool $isExternal;

    public function __construct(
        /**
         * The URI of the referenced resource.
         *
         * @var non-empty-string
         */
        public readonly string $uri,
    ) {
        $this->isExternal = true;
    }

    public function __toString(): string
    {
        return $this->uri;
    }
}
