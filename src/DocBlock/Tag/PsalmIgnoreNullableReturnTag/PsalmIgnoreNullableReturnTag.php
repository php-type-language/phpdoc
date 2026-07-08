<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreNullableReturnTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-ignore-nullable-return` tag ignores a possible `null` value
 * in the return type.
 */
final class PsalmIgnoreNullableReturnTag extends FlagTag {}
