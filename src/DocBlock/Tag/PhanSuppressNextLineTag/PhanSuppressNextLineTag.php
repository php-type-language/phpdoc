<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextLineTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@phan-suppress-next-line` tag silences the listed issue types
 * reported on the next line.
 */
final class PhanSuppressNextLineTag extends IssueListTag {}
