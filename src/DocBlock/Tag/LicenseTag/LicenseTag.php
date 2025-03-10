<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\LicenseTag;

use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\UriReference;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to indicate which license is applicable for the associated _Symbol_.
 *
 * The `"@license"` tag provides the user with the name and URL of the license
 * that is applicable to a _Symbol_ and any of its child elements.
 *
 * Whenever multiple licenses apply, there MUST be one "`@license"` tag per
 * applicable license.
 *
 * ```
 * "@license" [<url>] [name]
 * ```
 */
final class LicenseTag extends Tag
{
    public function __construct(
        string $name,
        public readonly ?UriReference $uri = null,
        /**
         * @var non-empty-string|null
         */
        public readonly ?string $license = null,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
