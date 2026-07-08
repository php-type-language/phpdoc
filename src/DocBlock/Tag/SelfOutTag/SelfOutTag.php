<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SelfOutTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@self-out` tag documents the refined type of `$this` after a method
 * call, letting callers see a narrower object type than the one they
 * started with.
 */
final class SelfOutTag extends TypedTag {}
