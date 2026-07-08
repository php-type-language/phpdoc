<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTraceTag;

use TypeLang\PhpDoc\DocBlock\Tag\VariableTag;

/**
 * The `@psalm-trace` tag outputs the inferred type of the given variable, for
 * debugging purposes.
 */
final class PsalmTraceTag extends VariableTag {}
