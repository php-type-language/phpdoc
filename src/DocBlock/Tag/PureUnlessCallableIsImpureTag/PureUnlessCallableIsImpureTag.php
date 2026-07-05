<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureUnlessCallableIsImpureTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The "@pure-unless-callable-is-impure" tag declares a function pure unless a
 * callable it receives is itself impure.
 */
final class PureUnlessCallableIsImpureTag extends FlagTag {}
