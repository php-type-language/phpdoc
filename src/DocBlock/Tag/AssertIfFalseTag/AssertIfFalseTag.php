<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertIfFalseTag;

use TypeLang\PhpDoc\DocBlock\Tag\AssertionTag;

/**
 * The `@assert-if-false` tag asserts the given type for a subject, but
 * only when the function returns `false`.
 */
final class AssertIfFalseTag extends AssertionTag {}
