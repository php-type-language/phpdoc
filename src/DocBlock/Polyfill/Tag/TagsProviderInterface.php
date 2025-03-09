<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Polyfill\Tag;

use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;

/**
 * @internal polyfill interface for the {@see \TypeLang\PHPDoc\DocBlock\Tag\TagsProviderInterface}
 *
 * @property-read iterable<array-key, TagInterface> $tags
 */
interface TagsProviderInterface {}
