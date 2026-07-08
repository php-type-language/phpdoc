<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ParamOutTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedVariableTag;

/**
 * The `@param-out` tag documents the type a by-reference argument holds after
 * the function or method returns.
 */
final class ParamOutTag extends TypedVariableTag {}
