<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\GlobalTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@global` tag documents a global variable that a function relies on.
 */
final class GlobalTag extends TypedVariableTag {}
