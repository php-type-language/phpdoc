<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmInheritorsTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@psalm-inheritors` tag restricts which classes are allowed to
 * extend or implement the described type.
 */
final class PsalmInheritorsTag extends TypedTag {}
