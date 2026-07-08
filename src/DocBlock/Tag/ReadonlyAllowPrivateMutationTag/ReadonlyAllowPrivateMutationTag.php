<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ReadonlyAllowPrivateMutationTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@readonly-allow-private-mutation` tag allows a readonly property to
 * be mutated from within the declaring class, rather than only during
 * initialization.
 */
final class ReadonlyAllowPrivateMutationTag extends FlagTag {}
