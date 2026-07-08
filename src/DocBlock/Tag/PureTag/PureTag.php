<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PureTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@pure` tag declares a function or method as pure, meaning it is
 * free of side effects: calling it repeatedly with the same arguments
 * always produces the same result, without observably mutating any state.
 */
final class PureTag extends FlagTag {}
