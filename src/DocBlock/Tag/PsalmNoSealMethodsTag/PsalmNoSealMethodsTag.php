<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmNoSealMethodsTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-no-seal-methods` tag allows a class to declare magic methods
 * beyond those already documented.
 */
final class PsalmNoSealMethodsTag extends FlagTag {}
