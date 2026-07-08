<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ReadonlyTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@readonly` tag declares that a property may only be written once, during
 * initialization.
 */
final class ReadonlyTag extends FlagTag {}
