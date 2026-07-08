<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ImmutableTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@immutable` tag declares a class as immutable, meaning none of its state
 * can change after construction.
 */
final class ImmutableTag extends FlagTag {}
