<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@assert` tag asserts that the given variable is narrowed to a
 * specified type once the call returns.
 */
final class AssertTag extends TypedVariableTag {}
