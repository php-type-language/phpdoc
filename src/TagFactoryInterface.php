<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc;

use TypeLang\PhpDoc\DocBlock\Tag\TagInterface;
use TypeLang\PhpDoc\Exception\PhpDocExceptionInterface;

/**
 * @template-covariant TTag of TagInterface = TagInterface
 */
interface TagFactoryInterface
{
    /**
     * @param non-empty-string $name
     * @return TTag
     * @throws PhpDocExceptionInterface in case of the tag cannot be created
     */
    public function create(string $name, string $suffix): TagInterface;
}
