<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc;

use TypeLang\PHPDoc\Tag\Description;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\TagProvider;
use TypeLang\PHPDoc\Tag\TagProviderInterface;

/**
 * @template-implements \IteratorAggregate<array-key, Tag>
 */
final class DocBlock implements TagProviderInterface, \IteratorAggregate
{
    use TagProvider;

    /**
     * @param iterable<array-key, Tag> $tags
     */
    public function __construct(
        private readonly Description $description = new Description(),
        iterable $tags = [],
    ) {
        $this->bootTagProvider($tags);
    }

    public function getDescription(): Description
    {
        return $this->description;
    }
}
