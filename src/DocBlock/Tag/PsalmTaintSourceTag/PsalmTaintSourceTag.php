<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSourceTag;

use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;

/**
 * The "@psalm-taint-source" tag marks the return value as a taint source
 * of the given type.
 */
final class PsalmTaintSourceTag extends IdentifierTag {}
