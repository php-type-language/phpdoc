<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressCurrentLineTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@phan-suppress-current-line` tag silences the listed issue types
 * reported on the current line.
 */
final class PhanSuppressCurrentLineTag extends IssueListTag {}
