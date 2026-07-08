<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\YieldTag;

use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@yield` tag documents the type yielded by a `Generator`, distinct
 * from the type it returns on completion.
 */
final class YieldTag extends TypedTag {}
