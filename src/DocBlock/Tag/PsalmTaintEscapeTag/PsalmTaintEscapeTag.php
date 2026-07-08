<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintEscapeTag;

use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;

/**
 * The `@psalm-taint-escape` tag marks a value as no longer tainted after
 * passing through the described element.
 */
final class PsalmTaintEscapeTag extends IdentifierTag {}
