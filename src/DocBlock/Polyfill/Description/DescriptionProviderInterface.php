<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Polyfill\Description;

use TypeLang\PHPDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PHPDoc\DocBlock\Description\OptionalDescriptionProviderInterface;

/**
 * @internal polyfill interface for the {@see \TypeLang\PHPDoc\DocBlock\Description\DescriptionProviderInterface}
 *
 * @property-read DescriptionInterface $description
 */
interface DescriptionProviderInterface extends
    OptionalDescriptionProviderInterface {}
