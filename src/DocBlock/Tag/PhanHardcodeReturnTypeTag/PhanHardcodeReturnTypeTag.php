<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanHardcodeReturnTypeTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@phan-hardcode-return-type` tag forces the documented return type
 * to be used instead of the type otherwise inferred from the method body.
 */
final class PhanHardcodeReturnTypeTag extends TypedTag {}
