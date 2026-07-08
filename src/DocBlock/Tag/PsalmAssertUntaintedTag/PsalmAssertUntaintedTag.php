<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmAssertUntaintedTag;

use TypeLang\PhpDoc\DocBlock\Tag\VariableTag;

/**
 * The `@psalm-assert-untainted` tag asserts that the given variable holds no
 * tainted data from this point on.
 */
final class PsalmAssertUntaintedTag extends VariableTag {}
