<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SealPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@seal-properties` tag forbids declaring magic properties beyond those
 * already documented.
 */
final class SealPropertiesTag extends FlagTag {}
