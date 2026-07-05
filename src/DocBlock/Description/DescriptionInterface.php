<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Description;

use TypeLang\PhpDoc\DocBlock\ComponentInterface;

/**
 * Any class that implements this interface is a description object
 * that can be represented as a raw string scalar value.
 */
interface DescriptionInterface extends ComponentInterface {}
