<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmOverridePropertyVisibilityTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-override-property-visibility` tag allows a subclass to
 * override an inherited property with a different visibility than the
 * parent declares.
 */
final class PsalmOverridePropertyVisibilityTag extends FlagTag {}
