<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanWriteOnlyTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-write-only` tag declares that a property may only ever be
 * written, never read.
 */
final class PhanWriteOnlyTag extends FlagTag {}
