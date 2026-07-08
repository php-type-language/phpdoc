<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintUnescapeTag;

use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;

/**
 * The `@psalm-taint-unescape` tag marks a value as tainted again after
 * passing through the described element, reversing an earlier
 * `@psalm-taint-escape`.
 */
final class PsalmTaintUnescapeTag extends IdentifierTag {}
