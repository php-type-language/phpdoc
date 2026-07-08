<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhpStanIgnoreTag;

use TypeLang\PhpDoc\DocBlock\Tag\IssueListTag;

/**
 * The `@​phpstan-ignore` tag silences the listed error identifiers reported
 * on the current line.
 */
final class PhpStanIgnoreTag extends IssueListTag {}
