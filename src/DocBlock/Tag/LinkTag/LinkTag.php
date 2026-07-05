<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\LinkTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\Tag\ReferenceTag;

/**
 * @template-extends ReferenceTag<UriReference>
 */
final class LinkTag extends ReferenceTag
{
    public function __construct(
        string $name,
        UriReference $uri,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $uri, $description);
    }
}
