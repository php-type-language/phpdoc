<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVarTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-ignore-var` tag excludes the immediately following `@var`
 * annotation from type inference, falling back to the inferred type
 * instead of the declared one.
 */
final class PsalmIgnoreVarTag extends FlagTag {}
