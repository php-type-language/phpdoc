<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressPreviousLineTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@phan-suppress-previous-line` tag silences the listed issue types
 * reported on the previous line.
 */
final class PhanSuppressPreviousLineTag extends IssueListTag {}
