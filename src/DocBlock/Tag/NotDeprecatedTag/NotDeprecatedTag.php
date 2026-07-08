<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NotDeprecatedTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@not-deprecated` tag marks an element as explicitly not deprecated,
 * overriding a `@deprecated` tag it would otherwise inherit from a parent.
 */
final class NotDeprecatedTag extends FlagTag {}
