<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealPropertiesTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-no-seal-properties` tag allows a class to declare magic
 * properties beyond those already documented.
 */
final class PsalmNoSealPropertiesTag extends FlagTag {}
