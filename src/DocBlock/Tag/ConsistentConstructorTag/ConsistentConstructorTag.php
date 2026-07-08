<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ConsistentConstructorTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@consistent-constructor` tag requires that all subclasses declare a
 * constructor compatible with the parent's.
 */
final class ConsistentConstructorTag extends FlagTag {}
