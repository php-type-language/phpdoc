<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AllowPrivateMutationTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@allow-private-mutation` tag allows a private-scope mutation of an
 * otherwise immutable property.
 */
final class AllowPrivateMutationTag extends FlagTag {}
