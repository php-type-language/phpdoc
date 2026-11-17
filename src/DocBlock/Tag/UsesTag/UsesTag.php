<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\UsesTag;

use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Tag\ReferenceTag;

/**
 * The `@uses` tag indicates that the described element uses the referenced one.
 *
 * @template-extends ReferenceTag<CodeReference>
 *
 * @generated from resources/model/phpdocumentor.xml, edit the model instead
 */
final class UsesTag extends ReferenceTag {}
