<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Reference\ReferenceInterface;

/**
 * A tag that points to another element or an external resource.
 *
 * @template-covariant TReference of ReferenceInterface = ReferenceInterface
 *
 * @property-read ReferenceInterface $reference The reference the tag points to.
 */
interface ReferencedTagInterface extends TagInterface {}
