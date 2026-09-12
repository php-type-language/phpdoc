<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AssertTag;

use TypeLang\PhpDoc\DocBlock\Tag\AssertionTag;

/**
 * The `@assert` tag asserts that the given subject is narrowed to the type it
 * carries once the call returns.
 */
final class AssertTag extends AssertionTag {}
