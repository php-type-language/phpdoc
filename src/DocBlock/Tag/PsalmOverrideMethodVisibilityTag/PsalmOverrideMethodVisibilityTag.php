<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmOverrideMethodVisibilityTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-override-method-visibility` tag allows a subclass to
 * override an inherited method with a different visibility than the parent
 * declares.
 */
final class PsalmOverrideMethodVisibilityTag extends FlagTag {}
