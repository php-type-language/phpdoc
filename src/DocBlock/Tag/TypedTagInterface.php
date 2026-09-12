<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\Type\TypeNode;

/**
 * A tag that carries a single type.
 *
 * @property-read TypeNode $type The type declared by the tag.
 */
interface TypedTagInterface extends TagInterface {}
