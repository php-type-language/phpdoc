<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Polyfill\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @internal polyfill interface for the {@see \TypeLang\PHPDoc\DocBlock\Tag\OptionalTypeProviderInterface}
 *
 * @property-read TypeStatement|null $type
 */
interface OptionalTypeProviderInterface {}
