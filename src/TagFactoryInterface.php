<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\DocBlock\TagDefinition\TagDefinitionInterface;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;

/**
 * @template-covariant TTag of TagInterface = TagInterface
 *
 * @template-extends \Traversable<non-empty-string, TagDefinitionInterface>
 */
interface TagFactoryInterface extends \Traversable, \Countable
{
    /**
     * @param non-empty-string $name
     * @return TTag
     * @throws PhpDocExceptionInterface
     */
    public function create(string $name, string $suffix): TagInterface;

    /**
     * @param non-empty-string $name
     */
    public function get(string $name): TagDefinitionInterface;

    /**
     * @return int<0, max>
     */
    public function count(): int;
}
