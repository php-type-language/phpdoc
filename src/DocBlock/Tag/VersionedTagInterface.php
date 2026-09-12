<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

/**
 * A tag that carries an optional version.
 *
 * @property-read ?non-empty-string $version The version the tag refers to, if any.
 */
interface VersionedTagInterface extends TagInterface {}
