<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmScopeThisTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@psalm-scope-this` tag binds the type of `$this` inside a
 * `Closure`, so the closure body is type-checked as if it were a method of
 * the given class.
 */
final class PsalmScopeThisTag extends TypedTag {}
