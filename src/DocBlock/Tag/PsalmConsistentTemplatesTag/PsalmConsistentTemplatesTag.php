<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmConsistentTemplatesTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-consistent-templates` tag requires that all subclasses use
 * the same template parameters as the parent.
 */
final class PsalmConsistentTemplatesTag extends FlagTag {}
