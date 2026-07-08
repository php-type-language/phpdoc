<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmIgnoreVariableMethodTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-ignore-variable-method` tag suppresses "undefined method"
 * issues for methods called on the annotated variable.
 */
final class PsalmIgnoreVariableMethodTag extends FlagTag {}
