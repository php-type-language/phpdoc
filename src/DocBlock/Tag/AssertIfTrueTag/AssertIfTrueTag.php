<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag;

use TypeLang\PhpDoc\DocBlock\Tag\AssertionTag;

/**
 * The `@assert-if-true` tag asserts the given type for a subject, but
 * only when the function returns `true`.
 */
final class AssertIfTrueTag extends AssertionTag {}
