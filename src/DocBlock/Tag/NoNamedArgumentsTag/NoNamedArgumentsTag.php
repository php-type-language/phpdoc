<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NoNamedArgumentsTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The "@no-named-arguments" tag indicates that the argument names may change
 * and must not be relied upon by callers.
 */
final class NoNamedArgumentsTag extends FlagTag {}
