<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamClosureThisTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@param-closure-this` tag documents the bound "$this" type of closure
 * passed as an argument.
 */
final class ParamClosureThisTag extends TypedVariableTag {}
