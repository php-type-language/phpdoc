<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreEndTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@codingStandardsIgnoreEnd` tag ends a block of code started by
 * `@codingStandardsIgnoreStart`, resuming coding-standard checks from this
 * point on.
 */
final class CodingStandardsIgnoreEndTag extends FlagTag {}
