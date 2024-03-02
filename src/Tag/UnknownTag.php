<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;

/**
 * @template T as DefinitionInterface
 *
 * @template-extends Tag<T>
 */
final class UnknownTag extends Tag {}
