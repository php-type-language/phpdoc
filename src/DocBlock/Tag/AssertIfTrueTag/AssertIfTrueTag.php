<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertIfTrueTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@assert-if-true` tag asserts the given type for a variable, but
 * only when the function returns `true`.
 */
final class AssertIfTrueTag extends TypedVariableTag {}
