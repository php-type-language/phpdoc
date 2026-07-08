<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreFalsableReturnTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-ignore-falsable-return` tag ignores a possible `false` value
 * in the return type.
 */
final class PsalmIgnoreFalsableReturnTag extends FlagTag {}
