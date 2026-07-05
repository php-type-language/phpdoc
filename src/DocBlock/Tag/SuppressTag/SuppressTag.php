<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SuppressTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The "@suppress" tag silences the diagnostics that would otherwise be
 * reported for an element.
 */
final class SuppressTag extends FlagTag {}
