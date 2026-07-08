<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanRealReturnTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@phan-real-return` tag documents the actual native return type of a
 * function or method, kept distinct from the documented `@return` type.
 */
final class PhanRealReturnTag extends TypedTag {}
