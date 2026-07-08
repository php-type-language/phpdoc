<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanSuppressNextNextLineTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@phan-suppress-next-next-line` tag silences the listed issue types
 * reported two lines below the annotation.
 */
final class PhanSuppressNextNextLineTag extends IssueListTag {}
