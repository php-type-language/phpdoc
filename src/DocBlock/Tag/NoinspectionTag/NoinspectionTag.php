<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NoinspectionTag;

use TypeLang\PhpDoc\DocBlock\Tag\IdentifierTag;

/**
 * The `@noinspection` tag suppresses the named IDE inspection for the
 * element that follows it.
 */
final class NoinspectionTag extends IdentifierTag {}
