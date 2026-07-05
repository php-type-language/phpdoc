<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\UsedByTag;

use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Tag\ReferenceTag;

/**
 * The "@used-by" tag indicates that the described element is used by the
 * referenced one.
 *
 * @template-extends ReferenceTag<CodeReference>
 */
final class UsedByTag extends ReferenceTag {}
