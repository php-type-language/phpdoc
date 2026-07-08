<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanConstructorUsedForSideEffectsTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-constructor-used-for-side-effects` tag declares that a
 * constructor's return value is intentionally discarded by callers,
 * suppressing the issue raised for an unused `new` expression.
 */
final class PhanConstructorUsedForSideEffectsTag extends FlagTag {}
