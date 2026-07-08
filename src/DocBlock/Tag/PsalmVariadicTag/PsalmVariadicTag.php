<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmVariadicTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-variadic` tag declares that a class's magic
 * `__call`/`__callStatic` methods accept a variadic list of arguments.
 */
final class PsalmVariadicTag extends FlagTag {}
