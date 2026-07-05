<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SeeTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\CodeReference;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\Tag\ReferenceTag;

/**
 * @template-extends ReferenceTag<UriReference|CodeReference>
 */
final class SeeTag extends ReferenceTag
{
    public function __construct(
        string $name,
        UriReference|CodeReference $reference,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $reference, $description);
    }
}
