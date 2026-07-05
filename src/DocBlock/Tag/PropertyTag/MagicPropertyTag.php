<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PropertyTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * A magic property that a class exposes for reading, writing, or both.
 */
abstract class MagicPropertyTag extends TypedVariableTag {}
