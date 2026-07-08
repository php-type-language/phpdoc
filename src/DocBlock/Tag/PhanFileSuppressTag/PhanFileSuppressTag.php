<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanFileSuppressTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@phan-file-suppress` tag silences the listed issue types for the
 * whole file it appears in, rather than just a single element or line.
 */
final class PhanFileSuppressTag extends IssueListTag {}
