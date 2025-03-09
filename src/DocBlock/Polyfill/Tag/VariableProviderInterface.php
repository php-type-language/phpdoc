<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Polyfill\Tag;

use TypeLang\PHPDoc\DocBlock\Tag\OptionalVariableProviderInterface;

/**
 * @internal polyfill interface for the {@see \TypeLang\PHPDoc\DocBlock\Tag\VariableProviderInterface}
 *
 * @property-read non-empty-string $variable
 */
interface VariableProviderInterface extends
    OptionalVariableProviderInterface {}
