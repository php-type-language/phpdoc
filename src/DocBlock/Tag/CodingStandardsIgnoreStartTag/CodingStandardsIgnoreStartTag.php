<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CodingStandardsIgnoreStartTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@codingStandardsIgnoreStart` tag starts a block of code excluded
 * from coding-standard checks, closed by a matching
 * `@codingStandardsIgnoreEnd`.
 */
final class CodingStandardsIgnoreStartTag extends FlagTag {}
