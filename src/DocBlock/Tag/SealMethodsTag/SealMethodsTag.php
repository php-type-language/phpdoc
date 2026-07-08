<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SealMethodsTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@seal-methods` tag forbids declaring magic methods beyond those already
 * documented.
 */
final class SealMethodsTag extends FlagTag {}
