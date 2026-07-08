<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmExternalMutationFreeTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-external-mutation-free` tag declares that a method never
 * mutates state observable from outside the object.
 */
final class PsalmExternalMutationFreeTag extends FlagTag {}
