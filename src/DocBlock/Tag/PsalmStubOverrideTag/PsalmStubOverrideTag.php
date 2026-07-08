<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmStubOverrideTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-stub-override` tag marks a stub declaration as intentionally
 * overriding the real signature.
 */
final class PsalmStubOverrideTag extends FlagTag {}
