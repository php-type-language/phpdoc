<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

/**
 * The `@psalm-check-type` tag reports an issue unless the inferred type of the
 * given variable is assignable to the expected type.
 */
final class PsalmCheckTypeTag extends CheckTypeTag {}
