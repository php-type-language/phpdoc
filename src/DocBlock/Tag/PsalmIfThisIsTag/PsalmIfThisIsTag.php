<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIfThisIsTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@psalm-if-this-is` tag narrows the type of `$this` inside a method
 * when the given type matches.
 */
final class PsalmIfThisIsTag extends TypedTag {}
