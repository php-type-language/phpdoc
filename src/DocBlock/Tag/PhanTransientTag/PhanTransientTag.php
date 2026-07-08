<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanTransientTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-transient` tag marks a property as excluded from
 * serialization.
 */
final class PhanTransientTag extends FlagTag {}
