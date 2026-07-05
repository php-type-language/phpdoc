<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\InheritanceTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * A relationship that makes a generic parent class, interface or trait
 * concrete by supplying its type arguments.
 */
abstract class InheritanceTag extends TypedTag {}
