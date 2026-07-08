<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSideEffectFreeTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-side-effect-free` tag declares a function or method as free
 * of side effects.
 */
final class PhanSideEffectFreeTag extends FlagTag {}
