<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\ComponentInterface;
use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * Representation of the phpdoc tag.
 *
 * @property-read non-empty-string $name Gets tag name string without the "@" prefix.
 * @property-read ?DescriptionInterface $description Gets an optional description object or {@see null} in case of
 *                description is not defined in the entry.
 */
interface TagInterface extends ComponentInterface {}
