<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreLineTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@​phpstan-ignore-line` tag silences any error reported on the
 * current line.
 */
final class PhpStanIgnoreLineTag extends FlagTag {}
