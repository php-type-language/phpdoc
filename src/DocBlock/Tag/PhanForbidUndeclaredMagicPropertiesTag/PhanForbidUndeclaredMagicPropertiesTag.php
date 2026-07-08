<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanForbidUndeclaredMagicPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-forbid-undeclared-magic-properties` tag forbids accessing
 * undeclared magic properties on the class it decorates, so only
 * documented properties may be accessed.
 */
final class PhanForbidUndeclaredMagicPropertiesTag extends FlagTag {}
