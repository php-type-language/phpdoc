<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\PsalmTaintSpecializeTag;

use TypeLang\PhpDoc\DocBlock\Tag\FlagTag;

/**
 * The `@psalm-taint-specialize` tag tracks tainted data separately per
 * call site, rather than merging taint information from all callers
 * together.
 *
 * @generated from resources/model/psalm.xml, edit the model instead
 */
final class PsalmTaintSpecializeTag extends FlagTag {}
