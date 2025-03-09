<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock;

use TypeLang\PHPDoc\DocBlock\Description\OptionalDescriptionProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TagInterface;
use TypeLang\PHPDoc\DocBlock\Tag\TagsProviderInterface;

/**
 * An interface represents structure containing a description and a set
 * of tags that describe an arbitrary DocBlock Comment in the code.
 *
 * @template-extends \Traversable<array-key, TagInterface>
 */
interface DocBlockInterface extends
    OptionalDescriptionProviderInterface,
    TagsProviderInterface,
    \Traversable,
    \Countable {}
