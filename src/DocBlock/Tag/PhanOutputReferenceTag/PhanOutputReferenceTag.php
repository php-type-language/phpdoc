<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PhanOutputReferenceTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@phan-output-reference` tag marks a by-reference argument as
 * output-only.
 */
final class PhanOutputReferenceTag extends FlagTag {}
