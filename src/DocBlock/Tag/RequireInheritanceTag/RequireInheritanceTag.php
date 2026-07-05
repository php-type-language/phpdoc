<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\RequireInheritanceTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * A constraint that a trait may only be used within a class related to the
 * given type.
 */
abstract class RequireInheritanceTag extends TypedTag {}
