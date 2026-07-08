<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanClosureScopeTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@phan-closure-scope` tag binds the type of `$this` inside a
 * `Closure`, so the closure body is analyzed as though bound to an
 * instance of the given class.
 */
final class PhanClosureScopeTag extends TypedTag {}
