<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanImpureTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phpstan-impure` tag declares a function or method as impure, that
 * is, having side effects.
 */
final class PhpStanImpureTag extends FlagTag {}
