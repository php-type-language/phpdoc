<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmMutationFreeTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-mutation-free` tag declares that a method never mutates any
 * state, whether observable from outside the object or purely internal to
 * it.
 */
final class PsalmMutationFreeTag extends FlagTag {}
