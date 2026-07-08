<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InternalTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@internal` tag marks an element as internal to its package, or documents
 * information meant only for the maintainers of that package.
 */
final class InternalTag extends FlagTag {}
