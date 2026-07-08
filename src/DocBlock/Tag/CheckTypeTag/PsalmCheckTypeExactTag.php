<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

/**
 * The `@psalm-check-type-exact` tag reports an issue unless the inferred type
 * of the given variable is exactly the expected type.
 */
final class PsalmCheckTypeExactTag extends CheckTypeTag {}
